<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_doc')]
    public function index(): Response
    {
        return $this->render('doc/index.html.twig', [
            "routes" => [
                [
                "title" => "Register",
                "method" => "POST",
                "url" => "/api/register",
                "description" => "Create a new account by providing an email, and password. This will register the user in the system.",
                "needToken" => false,
                "bodyJson" => [
                    "email" => "string (NOT NULL)",
                    "password" => "string (NOT NULL)",
                ],
                "responseJson" => [
                    "id" => "int (NOT NULL)",
                    "email" => "string (NOT NULL)",
                ]
            ], [
                "title" => "Login",
                "method" => "POST",
                "url" => "/api/login_check",
                "description" => "Allows the user to log in using their email and password, and obtain authentication tokens for further requests.",
                "needToken" => false,
                "bodyJson" => [
                    "username" => "your email : string (NOT NULL)",
                    "password" => "string (NOT NULL)",
                ],
                "responseJson" => [
                    "token" => "string (NOT NULL)",
                ]
            ], [
                "title" => "New order",
                "method" => "POST",
                "url" => "/api/order/new",
                "description" => "Simulate a payment and create a dated order linked to your account, including a list of products and their quantities.",
                "needToken" => true,
                "bodyJson" => [
                    [
                        "id" => "int (NOT NULL)",
                        "quantity" => "int (NOT NULL)"
                    ], ["..."]
                ],
                "responseJson" => [
                    "createdAt" => "datetime (NOT NULL)",
                    "id" => "int (NOT nULL)",
                    "owner" => [
                        "id" => "int (NOT NULL)",
                        "email" => "string (NOT NULL)",
                    ],
                    "productsItems" => [
                        [
                            "id" => "int (NOT NULL)",
                            "product" => [
                                "id" => "int (NOT NULL)",
                                "name" => "string (NOT NULL)",
                                "price" => "float (NOT NULL)",
                                "qrCodeUrl" => "string (NOT NULL)",
                                "imageUrl" => "string (NULL)",
                            ],
                            "quantity" => "int (NOT NULL)",
                        ], ["..."]
                    ]
                ]
            ], [
                "title" => "History of orders",
                "method" => "GET",
                "url" => "/api/order/history",
                "description" => "Retrieve all orders made by the logged-in account.",
                "needToken" => true,
                "bodyJson" => [
                ],
                "responseJson" => [
                    ["createdAt" => "datetime (NOT NULL)",
                        "id" => "int (NOT nULL)",
                        "owner" => [
                            "id" => "int (NOT NULL)",
                            "email" => "string (NOT NULL)",
                        ],
                        "productsItems" => [
                            [
                                "id" => "int (NOT NULL)",
                                "product" => [
                                    "id" => "int (NOT NULL)",
                                    "name" => "string (NOT NULL)",
                                    "price" => "float (NOT NULL)",
                                    "qrCodeUrl" => "string (NOT NULL)",
                                    "imageUrl" => "string (NULL)",
                                ],
                                "quantity" => "int (NOT NULL)",
                            ], ["..."]
                        ]
                    ]
                    , ["..."]
                ]
            ],
                [
                    "title" => "Get product",
                    "method" => "GET",
                    "url" => "/api/product/{id}",
                    "description" => "Retrieve a product by its ID, used when an item is scanned.",
                    "needToken" => true,
                    "bodyJson" => [],
                    "responseJson" => [
                        "id" => "int (NOT NULL)",
                        "name" => "string (NOT NULL)",
                        "price" => "float (NOT NULL)",
                        "qrCodeUrl" => "string (NOT NULL)",
                        "imageUrl" => "string (NULL)",
                    ]

                ],
            ],


        ]);
    }
}
