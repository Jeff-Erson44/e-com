<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\ContentCart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $cart = $em->getRepository(Cart::class)->findActiveCart($user);
        $cartUser = $em->getRepository(ContentCart::class)->findByCart($cart);

        $total = 0;

        foreach($cartUser as $cu){
            $quantity = $cu->getQuantity();
            $product = $cu->getProduct()->first();
            $prix = $product->getPrice();
            
            $ccTotal = $quantity * $prix;
            $total += $ccTotal;
        }

        return $this->render('cart/index.html.twig', [
            'cartUser' => $cartUser,
            'total' => $total
        ]);
    }
}
