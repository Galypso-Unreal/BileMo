<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[Route('/api', name: "api_")]
class ProductController extends AbstractController
{
    #[Route('/products', name: 'api_products', methods: 'GET')]
    public function getAllProducts(ProductRepository $productRepository, SerializerInterface $serializer, Request $request, TagAwareCacheInterface $cache): JsonResponse
    {
        if ($this->isGranted("ROLE_ADMIN") === true) {

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
                    $productsList = $productRepository->findAll();
                } else {
                    $productsList = $productRepository->findAllWithPagination($page, $limit);
                }

                return $serializer->serialize($productsList, 'json');
            });

            return new JsonResponse($jsonProductsList, Response::HTTP_OK, [], true);
        }
        return throw new HttpException('403', 'You not authorized to see all products');
    }

    #[Route('/products/{id}', name: 'api_product', methods: 'GET')]
    public function getOneProductById(int $id, ProductRepository $productRepository, SerializerInterface $serializer, TagAwareCacheInterface $cache): JsonResponse
    {
        if ($this->isGranted("ROLE_ADMIN") === true) {

            $idCache = "getOneProduct-" . $id;

            $jsonProduct = $cache->get($idCache, function (ItemInterface $item) use ($productRepository, $serializer, $id) {
                $item->tag("ProductsCache");

                $product = $productRepository->find($id);

                if ($product) {
                    return $serializer->serialize($product, 'json');
                }
                return throw new HttpException('404', "The ID doesn't exists");
            });
            return new JsonResponse($jsonProduct, Response::HTTP_OK, [], true);
        }
        return throw new HttpException('403', 'You not authorized to see the product');
    }

    #[Route('/products/{id}', name: 'api_delete_product', methods: 'DELETE')]
    public function deleteOneProductById(int $id, EntityManagerInterface $entityManagerInterface, ProductRepository $productRepository, SerializerInterface $serializer, TagAwareCacheInterface $cache): JsonResponse
    {
        if ($this->isGranted("ROLE_ADMIN") === true) {
            $product = $productRepository->find($id);

            if ($product) {
                $cache->invalidateTags(['ProductsCache']);
                $entityManagerInterface->remove($product);
                $entityManagerInterface->flush();
                return new JsonResponse(null, Response::HTTP_NO_CONTENT);
            }
            return throw new HttpException('404', "The ID doesn't exists");
        }
        return throw new HttpException('403', 'You not authorized to see the product');
    }

    #[Route('/products', name: 'api_product_create', methods: 'POST')]
    public function createProduct(ValidatorInterface $validator, Request $request, ProductRepository $productRepository, SerializerInterface $serializer, EntityManagerInterface $entityManagerInterface): JsonResponse
    {
        if ($this->isGranted("ROLE_ADMIN") === true) {
            $product = $serializer->deserialize($request->getContent(), Product::class, 'json');

            $errors = $validator->validate($product);

            if ($errors->count() > 0) {
                return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], 'true');
            }

            $entityManagerInterface->persist($product);
            $entityManagerInterface->flush();

            $jsonProduct = $serializer->serialize($product, 'json');

            return new JsonResponse($jsonProduct, Response::HTTP_CREATED, [], true);
        }
        return throw new HttpException('403', 'You not authorized to create a product');
    }
}
