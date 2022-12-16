<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use App\Entity\ItemOrder;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandController extends AbstractController
{
    #[Route('/command/create', name: 'command_create')]
    public function index(SessionInterface $session, ProductRepository $productRepo, EntityManagerInterface $em): Response
    {

        $tokenProvider = $this->container->get('security.csrf.token_manager');
        $token = $tokenProvider->getToken('stripe_token')->getValue();

        $cart = $session->get("cart",[]);
        $command = new Order;
        $totalPrice = 0;
        $lineItems = [];

        foreach($cart as $productId => $productQuantity){
            
            $itemOrder = new ItemOrder;
            $product = $productRepo->find($productId);
            $totalPrice += $product->getPrice() * $productQuantity;

            $itemOrder ->setQuantity($productQuantity)
                        ->setProductLink($product)
                        ->setOrderLink($command);

            $em->persist($itemOrder);

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                    'name' => $product->getName(),
                    ],
                    'unit_amount' => $product->getPrice(),
                ],
                'quantity' => $productQuantity,
            ];
        }

        $command->setTotalPrice($totalPrice);
        ;

        $em->persist($command);
        $em->flush();
       // $session->set("cart",[]);

        \Stripe\Stripe::setApiKey('');
        $session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => 'http://localhost:8000/command/payment_success/' . $token,
            'cancel_url' => 'http://localhost:8000/command/payment_error'
            ]);
        
    
        return $this->redirect($session->url, 303);

        //return $this->redirectToRoute('app_cart');

        
    }

    #[Route('/command/payment_success/{token}', name: 'command_payment_success')]
    public function payment_success(SessionInterface $session,$token): Response
    {
        if($this->isCsrfTokenValid('stripe_token',$token)){
            $session->set("cart",[]);
            dd("OK");
        }else{
            dd("FALSE SUCCESS");
        }
        

        
    }

    #[Route('/command/payment_error', name: 'command_payment_error')]
    public function payment_error(): Response
    {
        dd("Retry");
        
    }
}
