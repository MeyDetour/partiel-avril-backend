<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/order')]
final class ApiOrderController extends AbstractController
{
    #[Route('/new', name: 'new_order', methods: "POST")]
    public function index(Request $request, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $data = json_decode($request->getContent(), true);
        if (!$data) return $this->json(["message" => "no data send", 409]);

        $productsIds = $data['products'];
        if (count($productsIds) === 0) return $this->json(["message" => "no products", 400]);


        $order = new Order();
        $order->setOwner($user);

        foreach ($productsIds as $id) {
            $product = $productRepository->find($id);
            if (!$product) return $this->json(["message" => "product $id not found", 404]);
            $order->addProduct($product);
        }

        $entityManager->persist($order);
        $entityManager->flush();


        return $this->json($order, 200,[], ["groups" => ['order']]);
    }
    #[Route('/history', name: 'order_history', methods: "GET")]
    public function history(Request $request): Response
    {
        return $this->json($this->getUser()->getOrders(),200,[],['groups'=>['order']]);
    }
}
