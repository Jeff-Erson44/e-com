<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $em ){
        $prod = $em->getRepository(Product::class)->findAll();

        return $this->render('default/index.html.twig', [
            'prod' => $prod
        ]);
    }

    #[Route('/product/{id}', name:'product_detail')]
    public function detail(Product $prodDetail = null){

        return $this->render('default/detail.html.twig', [
            'prodDetail' => $prodDetail
        ]);
    }

    #[Route('/cart/{id}', name: 'productInCart')]
    public function addProductInCart(Product $product, EntityManagerInterface $em):Response{
        if($product){
        // je vérifie qu'un utilisateur existe
            $user = $this->getUser();
            if(!$user){
                return $this->redirectToRoute('app_login');
            }else{
                $user_id= $user->getId();
                $cart = $em->getRepository(Cart::class)->findActiveCart($user_id);
                dump($user_id);
                
                if($cart){
                    // si le produit existe déja dans le panier (fonction dans repo)
                    // alors on ajoute à la quantite 
                        // sinon on créé un content cart avec le produit 
                }else{
                    // on cré un panier
                    // on cré un content cart 
                    // on met à jour le content cart product 
                }
            //     // on calcul le prix du panier (fonction dans repo)
            //     // calcul du nombre d'article du panier (fonction dans repo)
            //     // return value 
            }
        }
        return $this->render('/default/index.html.twig', [
            $user_id

        ]);
    }
    // je vérifie qu'aucun panier existe deja pour cet utilisateur 
}
