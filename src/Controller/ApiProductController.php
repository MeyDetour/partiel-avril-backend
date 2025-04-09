<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/product')]
final class ApiProductController extends AbstractController
{


    #[Route(path: "/all", name: 'app_api_products', methods: "GET")]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->json($productRepository->findAll(), 200, [], ["groups" => ["products"]]);
    }

    #[Route(path: "/{id}", name: 'app_api_product', methods: "GET")]
    public function getProduct(ProductRepository $productRepository,$id): Response
    {
        $product = $productRepository->find($id);
        if (!$product)return $this->json(["message"=>"Product not found !"],404);
        return $this->json($product, 200, [], ["groups" => ["products"]]);
    }
}
