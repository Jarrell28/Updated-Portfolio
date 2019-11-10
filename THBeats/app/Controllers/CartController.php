<?php
/**
 * Created by PhpStorm.
 * User: jhous
 * Date: 1/6/2019
 * Time: 9:37 AM
 */

namespace App\Controllers;
use Illuminate\Database\Query\Builder;
use Slim\Views\Twig;
use App\Controllers\SessionStorage;


class CartController
{

    protected $view;
    protected $songs;

 public function __construct(Twig $view, Builder $songs)
 {
     $this->view = $view;
     $this->songs = $songs;
 }

 public function index($request, $response){
     $storage = new SessionStorage;
     $cart = $storage->getStorage('cart');
     $songs = $this->songs->get();
     $cartItems = [];
     $total = 0;

     foreach($cart as $key => $val){
        $song = $songs->where('id', $key)->first();
        $total += $song->Price;
        $cartItems[] = $song;
     }

     return $this->view->render($response, 'cart/index.twig', ['cart' => $cartItems, 'total' => $total]);
 }

 public function cartEmail($request, $response){
     if(isset($_POST['cart-email'])){
         $_SESSION['email1'] = trim($_POST['cart-email']);

         return $response->withRedirect('/THBeats/public/cart');
     }
 }
}