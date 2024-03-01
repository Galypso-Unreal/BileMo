<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\FetchLinks;
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

    #[Route('/products', name: 'products', methods: 'GET')]
    /**
     * The function getAllProducts retrieves a list of products from a cache or database based on the
     * provided page and limit parameters, and returns the list as a JSON response.
     * example of request with parameters : http://localhost:8000/api/products/?page=1&limit=2
     */
    #[OA\Response(
        response: 200,
        description: 'Returns all products of API',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Product::class, groups: ['getProducts']))
        )
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'path',
        required: false,
        description: 'The field used to get number of page do you want to recive',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'limit',
        in: 'path',
        required: false,
        description: 'The field used to get number of products do you want per page',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'products')]


    public function getAllProducts(FetchLinks $fetchLink, ProductRepository $productRepository, SerializerInterface $serializer, Request $request, TagAwareCacheInterface $cache): JsonResponse
    {

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 0);

        if ($page <= 0 or $limit < 0) {
            return throw new HttpException('404', "page need to be 1 or more and limit can be 0 or more");
        }

        /**
         * Create an ID cache
         */
        $idCache = "getAllProducts-".$page."-".$limit;

        $jsonProductsList = $cache->get($idCache, function (ItemInterface $item) use ($productRepository, $page, $limit, $serializer, $fetchLink) {
            $item->tag("ProductsCache");
            if ($page > 0 and $limit === 0) {
                $productsList = $productRepository->findAllProducts();
                return $serializer->serialize($productsList, 'json', ['groups' => 'getProducts']);
            } else {

                $productList = $productRepository->findAllWithPagination($page, $limit);
                $productList_next = $productRepository->findAllWithPagination($page + 1, $limit);

                $links = $fetchLink->generatePaginationLinks("products", $limit, $page, $productList_next);

                $merge = $fetchLink->merge($productList, $links, "products");

                return $serializer->serialize($merge, 'json', ['groups' => 'getProducts', 'json_encode_options' => JSON_UNESCAPED_SLASHES]);
            }
        });

        return new JsonResponse($jsonProductsList, Response::HTTP_OK, [], true);

    }

    #[Route('/products/{id}', name: 'product', methods: 'GET')]
    /**
     * This function retrieves a product by its ID from a cache or database, serializes it to JSON, and
     * returns it as a JSON response.
     * example of request with parameters : http://localhost:8000/api/products/2 : 2 is the id of product
     */
    #[OA\Response(
        response: 200,
        description: 'Returns one product of API',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Product::class, groups: ['getProduct']))
        )
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'The ID of the product you want to retrieve',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'products')]


    public function getOneProductById(FetchLinks $fetchLink, int $id, ProductRepository $productRepository, SerializerInterface $serializer, TagAwareCacheInterface $cache): JsonResponse
    {

        $idCache = "getOneProduct-".$id;

        $jsonProduct = $cache->get($idCache, function (ItemInterface $item) use ($productRepository, $serializer, $id, $fetchLink) {
            $item->tag("ProductsCache");

            $product = $productRepository->findById($id);

            if ($product) {
                $links = $fetchLink->generateLinks("product", $id);

                $merge = $fetchLink->merge($product, $links);

                return $serializer->serialize($merge, 'json', ['groups' => 'getProduct', 'json_encode_options' => JSON_UNESCAPED_SLASHES]);
            }
            return throw new HttpException('404', "The ID doesn't exists");
        });
        return new JsonResponse($jsonProduct, Response::HTTP_OK, [], true);

    }
    #[Route('/products/{id}', name: 'delete_product', methods: 'DELETE')]


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
    #[Route('/products', name: 'create_product', methods: 'POST')]

    
    public function createProduct(ValidatorInterface $validator, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManagerInterface, TagAwareCacheInterface $cache): JsonResponse
    {
        $product = $serializer->deserialize($request->getContent(), Product::class, 'json');

        $errors = $validator->validate($product);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json', ['groups' => 'getProducts']), JsonResponse::HTTP_BAD_REQUEST, [], 'true');
        }

        $entityManagerInterface->persist($product);
        $entityManagerInterface->flush();
        $cache->invalidateTags(['ProductsCache']);
        $jsonProduct = $serializer->serialize($product, 'json', ['groups' => 'getProducts']);

        return new JsonResponse($jsonProduct, Response::HTTP_CREATED, [], true);

    }
}
