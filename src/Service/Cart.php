<?php 
namespace App\Service;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    private $session;

    public function __construct(RequestStack $requestStack)
    {
        $this->session=$requestStack->getSession(); 
    }

    public function add(Product $product, int $quantity)
    {
        $session = $this->session;
        $cart = $this->getCart();
        $cart[$product->getId()] = (int)$quantity;
        $session->set("cart",$cart);
    }

    public function getCart(){
        return $this->session->get("cart",[]);
    }

   
}




?>