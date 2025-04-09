<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\ProductInCart;
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

        $productsDATA = $data['products'];
        if (count($productsDATA) === 0) return $this->json(["message" => "no products", 400]);


        $order = new Order();
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setOwner($user);

        foreach ($productsDATA as $productsDATUM) {
            if (empty($productsDATUM["id"]) || empty($productsDATUM["quantity"])) return $this->json(["message" => "You must provide quantity and id for each product"], 400);

            $product = $productRepository->find($productsDATUM["id"]);
            if (!$product) return $this->json(["message" => "product " . $productsDATUM["id"] . "not found"] ,404);

            $productItemForCart = new ProductInCart();
            $productItemForCart->setQuantity($productsDATUM["quantity"]);
            $productItemForCart->setProduct($product);

            $order->addProductsItem($productItemForCart);
        }

        $entityManager->persist($order);
        $entityManager->flush();


        return $this->json($order, 200, [], ["groups" => ['order']]);
    }

    #[Route('/history', name: 'order_history', methods: "GET")]
    public function history(Request $request): Response
    {
        return $this->json($this->getUser()->getOrders(), 200, [], ['groups' => ['order']]);
    }


}
