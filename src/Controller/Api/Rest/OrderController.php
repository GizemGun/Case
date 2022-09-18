<?php

namespace App\Controller\Api\Rest;

use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class OrderController extends AbstractController
{
    // TODO: Create new order
    #[Route("/create-order", name: "create_order", methods: ['POST'])]
    public function createOrder(Request $request, OrderRepository $orderRepository, UserInterface $user): JsonResponse
    {
        $postData = [];
        $jsonData = json_decode($request->getContent(), true);
        if (!is_null($jsonData)) $postData = $jsonData;
        $postData = array_merge($postData, $request->query->all());
        $postData["user"] = $user->getId();
        if (!array_key_exists("products", $postData) || count($postData["products"]) == 0) {
            return $this->json(["isSuccess" => false, "message" => "You have to send product", "data" => []]);
        }
        return $this->json($orderRepository->new($postData));
    }

    // TODO: Update order
    #[Route("/update-order", name: "update_order", methods: ['POST'])]
    public function updateOrder(Request $request, OrderRepository $orderRepository, UserInterface $user): JsonResponse
    {
        $postData = [];
        $jsonData = json_decode($request->getContent(), true);
        if (!is_null($jsonData)) $postData = $jsonData;
        $postData = array_merge($postData, $request->query->all());
        $postData["user"] = $user->getId();
        return $this->json($orderRepository->update($postData));
    }

    // TODO: Update shipping date
    #[Route("/update-shipping-date", name: "update_shipping_date", methods: ['POST'])]
    #[isGranted('ROLE_ADMIN')]
    public function updateShippingDate(Request $request, OrderRepository $orderRepository): JsonResponse
    {
        $postData = [];
        $jsonData = json_decode($request->getContent(), true);
        if (!is_null($jsonData)) $postData = $jsonData;
        $postData = array_merge($postData, $request->query->all());
        return $this->json($orderRepository->updateShipping($postData));
    }

    // TODO: List all order
    #[Route("/get-user-order", name: "get_user_order", methods: ['POST'])]
    public function getUserOrder(Request $request, OrderRepository $orderRepository, UserInterface $user): JsonResponse
    {
        $postData = [];
        $jsonData = json_decode($request->getContent(), true);
        if (!is_null($jsonData)) $postData = $jsonData;
        $postData = array_merge($postData, $request->query->all());
        $postData["user"] = $user->getId();
        return $this->json($orderRepository->getUserOrders($postData));
    }

    // TODO: List order detail
    #[Route("/get-order-detail", name: "get_order_detail", methods: ['POST'])]
    public function getOrderDetail(Request $request, OrderProductRepository $orderProductRepository, UserInterface $user): JsonResponse
    {
        $postData = [];
        $jsonData = json_decode($request->getContent(), true);
        if (!is_null($jsonData)) $postData = $jsonData;
        $postData = array_merge($postData, $request->query->all());
        $postData["user"] = $user->getId();
        if (!array_key_exists("orderCode", $postData) || is_null($postData["orderCode"])) {
            return $this->json(["isSuccess" => false, "message" => "You have to send order", "data" => []]);
        }
        return $this->json($orderProductRepository->getOrderDetails($postData));
    }
}