<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\ContentCart;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $em)
    {
        $prod = $em->getRepository(Product::class)->findAll();

        return $this->render('default/index.html.twig', [
            'prod' => $prod
        ]);
    }

    #[Route('/product/{id}', name: 'product_detail')]
    public function detail(Product $prodDetail = null)
    {

        return $this->render('default/detail.html.twig', [
            'prodDetail' => $prodDetail
        ]);
    }

    #[Route('/cart/{id}', name: 'productInCart')]
    public function addProductInCart(Product $product, EntityManagerInterface $em): Response
    {
        if ($product) {
            // je vérifie qu'un utilisateur existe
            $user = $this->getUser();
            if (!$user) {
                return $this->redirectToRoute('app_login');
            } else {
                $user_id = $user->getId();
                $cart = $em->getRepository(Cart::class)->findActiveCart($user_id);

                if ($cart) {
                    $productInCart = $em->getRepository(ContentCart::class)->findProductInCart($product, $cart);
                    // si le produit existe déja dans le panier (fonction dans repo)
                    if($productInCart){
                        $content_cart = $productInCart;
                        // alors on ajoute à la quantite
                        $quantity = $cart->getQuantity() +1;
                        $cart->setQuantity($quantity);
                        
                    }else{
                        $content_cart = new ContentCart();
                        $content_cart->setQuantity(1);
                        $content_cart->setCart($cart);
                        $content_cart->addProduct($product); //on met à jour le content cart product
                        $content_cart->setAddedDate(new \DateTime());
                        $em->persist($content_cart);
                        $em->flush();
                    }
                    // sinon on créé un content cart avec le produit 
                } else {
                    //on créer un panier
                    $cart = new Cart();
                    $cart->setUser($user);
                    $cart->setState(false);
                    $em->persist($cart);
                    $em->flush();

                    //on créer un content cart
                    $content_cart = new ContentCart();
                    $content_cart->setQuantity(1);
                    $content_cart->setCart($cart);
                    $content_cart->addProduct($product); //on met à jour le content cart product
                    $content_cart->setAddedDate(new \DateTime());
                    $em->persist($content_cart);
                    $em->flush();
                }
                //     // on calcul le prix du panier (fonction dans repo)
                //     // calcul du nombre d'article du panier (fonction dans repo)
                //     // return value 
            }
        } else {
            // flash
        }
        return new JsonResponse([
            $cart
        ]);
    }
    // je vérifie qu'aucun panier existe deja pour cet utilisateur 
}
