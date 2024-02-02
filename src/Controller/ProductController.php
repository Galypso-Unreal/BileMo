<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;

#[Route('/api', name: "api_")]
class ProductController extends AbstractController
{
    
    #[Route('/products', name: 'api_products', methods: 'GET')]
    /**
     * The function getAllProducts retrieves a list of products from a cache or database based on the
     * provided page and limit parameters, and returns the list as a JSON response.
     * 
    */
    #[OA\Response(
        response: 200,
        description: 'Returns all products of API',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Product::class, groups: ['getProducts']))
        )
    )]
    #[OA\Tag(name: 'products')]
    #[Security(name: 'Bearer')]
    public function getAllProducts(ProductRepository $productRepository, SerializerInterface $serializer, Request $request, TagAwareCacheInterface $cache): JsonResponse
    {

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 0);

        if ($page <= 0 or $limit < 0) {
            return throw new HttpException('404', "page need to be 1 or more and limit can be 0 or more");
        }

        /**
         * Create an ID cache
         */
        $idCache = "getAllProducts-" . $page . "-" . $limit;


        $jsonProductsList = $cache->get($idCache, function (ItemInterface $item) use ($productRepository, $page, $limit, $serializer) {
            $item->tag("ProductsCache");

            if ($page > 0 and $limit == 0) {
                $productsList = $productRepository->findAllProducts();
            } else {
                $productsList = $productRepository->findAllWithPagination($page, $limit);
            }

            return $serializer->serialize($productsList, 'json',['groups' => 'getProducts']);
        });

        return new JsonResponse($jsonProductsList, Response::HTTP_OK, [], true);
    }

    #[Route('/products/{id}', name: 'api_product', methods: 'GET')]
    public function getOneProductById(int $id, ProductRepository $productRepository, SerializerInterface $serializer, TagAwareCacheInterface $cache): JsonResponse
    {

        $idCache = "getOneProduct-" . $id;

        $jsonProduct = $cache->get($idCache, function (ItemInterface $item) use ($productRepository, $serializer, $id) {
            $item->tag("ProductsCache");

            $product = $productRepository->find($id);

            if ($product) {
                return $serializer->serialize($product, 'json',['groups' => 'getProducts']);
            }
            return throw new HttpException('404', "The ID doesn't exists");
        });
        return new JsonResponse($jsonProduct, Response::HTTP_OK, [], true);
    }

    #[Route('/products/{id}', name: 'api_delete_product', methods: 'DELETE')]
    public function deleteOneProductById(int $id, EntityManagerInterface $entityManagerInterface, ProductRepository $productRepository, SerializerInterface $serializer, TagAwareCacheInterface $cache): JsonResponse
    {
        $product = $productRepository->find($id);

        if ($product) {
            $cache->invalidateTags(['ProductsCache']);
            $entityManagerInterface->remove($product);
            $entityManagerInterface->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return throw new HttpException('404', "The ID doesn't exists");
    }

    #[Route('/products', name: 'api_product_create', methods: 'POST')]
    public function createProduct(ValidatorInterface $validator, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManagerInterface): JsonResponse
    {
        $product = $serializer->deserialize($request->getContent(), Product::class, 'json');

        $errors = $validator->validate($product);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json',['groups' => 'getProducts']), JsonResponse::HTTP_BAD_REQUEST, [], 'true');
        }

        $entityManagerInterface->persist($product);
        $entityManagerInterface->flush();

        $jsonProduct = $serializer->serialize($product, 'json',['groups' => 'getProducts']);

        return new JsonResponse($jsonProduct, Response::HTTP_CREATED, [], true);
    }
}
