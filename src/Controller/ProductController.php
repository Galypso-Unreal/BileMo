<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api', name:"api_")]
class ProductController extends AbstractController
{
    #[Route('/products', name: 'api_products', methods:'GET')]
    public function getAllProducts(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $products = $productRepository->findAll();

        $jsonProducts = $serializer->serialize($products,'json');

        return new JsonResponse($jsonProducts,Response::HTTP_OK,[],true);
    }

    #[Route('/product/{id}', name: 'api_product', methods:'GET')]
    public function getOneProductById(int $id,ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $product = $productRepository->find($id);

        if($product){
            $jsonProduct = $serializer->serialize($product,'json');
            return new JsonResponse($jsonProduct,Response::HTTP_OK,[],true);
        }
        return new JsonResponse(null,Response::HTTP_NOT_FOUND);
        
    }

}
