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

#[Route('/api', name: "api_")]
class ProductController extends AbstractController
{
    #[Route('/products', name: 'api_products', methods: 'GET')]
    public function getAllProducts(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        if ($this->isGranted("ROLE_ADMIN") === true) {
            $products = $productRepository->findAll();

            $jsonProducts = $serializer->serialize($products, 'json');

            return new JsonResponse($jsonProducts, Response::HTTP_OK, [], true);
        }
        return throw new HttpException('403', 'You not authorized to see all products');
    }

    #[Route('/products/{id}', name: 'api_product', methods: 'GET')]
    public function getOneProductById(int $id, ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        if ($this->isGranted("ROLE_ADMIN") === true) {
            $product = $productRepository->find($id);

            if ($product) {
                $jsonProduct = $serializer->serialize($product, 'json');
                return new JsonResponse($jsonProduct, Response::HTTP_OK, [], true);
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
