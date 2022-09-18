<?php

namespace App\Controller\Api\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'test')]
    public function index(): JsonResponse
    {
        return $this->json([]);
    }
}