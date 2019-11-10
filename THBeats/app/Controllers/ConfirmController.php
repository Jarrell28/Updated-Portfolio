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


class ConfirmController
{

    protected $view;
    protected $songs;
    protected $orders;

 public function __construct(Twig $view, Builder $songs, Builder $orders)
 {
     $this->view = $view;
     $this->songs = $songs;
     $this->orders = $orders;
 }

 public function index($request, $response, $args){
     if(isset($_GET['tx'])){
         $storage = new SessionStorage;
         $cart = $storage->getStorage('cart');
         $songs = $this->songs->get();
         $cartItems = [];

         foreach($cart as $key => $val){
            $song = $songs->where('id', $key)->first();
            $cartItems[] = $song;
         }

         try{
             $this->orders->insert(['email' => $_SESSION['email1'], 'amount' => $_GET['amt'], 'currency' => $_GET['cc'], 'status' => $_GET['st'], 'order_date' => date('M, d, Y')]);
         } catch(\Exception $e){
             return $response->withRedirect('/THBeats/public');
         }



         return $this->view->render($response, 'confirm/index.twig', ['cart' => $cartItems]);
     } else{
         return $response->withRedirect('/THBeats/public');
     }
 }
}