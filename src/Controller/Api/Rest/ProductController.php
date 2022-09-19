<?php

namespace App\Controller\Api\Rest;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    // TODO: List all order
    #[Route("/get-products", name: "get_products", methods: ['POST'])]
    public function getUserOrder(ProductRepository $productRepository): JsonResponse
    {
        return $this->json($productRepository->getAll());
    }
}