<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\Cart;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index( SessionInterface $session, ProductRepository $productRepo): Response
    {
        //dd($session->get("cart"));
        $cart = $session->get("cart");
        $productsInCart = $productRepo->findById(array_keys($cart));
        $tva20 = $this->getParameter('codeTVA');

       // dd($productsInCart);

        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'productsInCart' => $productsInCart,
            'cart' => $cart,
            'tva' => $tva20
        ]);
    }

    //Version avec Service
    #[Route('/cart/add/{id}', name: 'app_cartadd')]
    public function addCart( Product $product, Cart $cart, Request $request): Response
    {
        $quantity = $request->get('productQuantity');
        
        $cart->add($product,$quantity);
        $previousUrl = $request->headers->get("referer");
        return $this->redirect($previousUrl);


    // Version sans Service
    // #[Route('/cart/add/{id}', name: 'app_cartadd')]
    // public function addCart( Product $product, SessionInterface $session, Request $request): Response
    // {
    //     $quantity = $request->get('productQuantity');
    //     $cart = $session->get('cart',[]);
    //     $cart[$product->getId()] = (int)$quantity;
    //     $session->set("cart",$cart);
    //     $previousUrl = $request->headers->get("referer");
    //     return $this->redirect($previousUrl);

    //_________________________________________________________________________

      //  $productId = $request->get('id');
       
/*
        if(array_key_exists($product->getId(),$cart)){
            $cart[$product->getId()]++;
        }else{
            $cart[$product->getId()] = 1;
        }

        $session->set("cart",$cart);

        return $this->redirectToRoute(route:'app_cart');
     */
    }
}
