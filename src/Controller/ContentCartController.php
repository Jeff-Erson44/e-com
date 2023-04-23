<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContentCartController extends AbstractController
{
    #[Route('/content/cart', name: 'app_content_cart')]
    public function index(): Response
    {
        return $this->render('content_cart/index.html.twig', [
            'controller_name' => 'ContentCartController',
        ]);
    }
}
