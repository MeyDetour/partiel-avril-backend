<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    #[Route('/orders', name: 'app_orders_index')]
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('admin/order/index.html.twig', [
            "orders" => $orderRepository->findAll()
        ]);
    }
    #[Route('/order/{id}', name: 'app_order')]
    public function show(Order $order): Response
    {
        return $this->render('admin/order/show.html.twig', [
"order"=>$order
        ]);
    }
}
