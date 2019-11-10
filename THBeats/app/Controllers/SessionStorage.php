<?php
/**
 * Created by PhpStorm.
 * User: jhous
 * Date: 1/5/2019
 * Time: 5:46 PM
 */

namespace App\Controllers;

use Slim\Views\Twig;

class SessionStorage
{

    public function getStorage($storage){
        if(isset($_SESSION[$storage])){
            return $_SESSION[$storage];
        } else {
            return "No Items In Cart";
        }
    }

    public function addToStorage($request, $response, $args){
        $storage = $args['cart'];
        $id = $args['id'];
        if(isset($_SESSION[$storage])){
            isset($_SESSION[$storage][$id]) ? $_SESSION[$storage][$id] = 1 : $_SESSION[$storage][$id] = 1;
            echo $_SESSION[$storage][$id];
            exit;
        } else{
            $_SESSION[$storage][$id] = 1;
        }

    }

    public function deleteFromStorage($request, $response, $args){
        $storage = $args['cart'];
        $id = $args['id'];
        if(isset($_SESSION[$storage])){
            isset($_SESSION[$storage][$id]) ? $_SESSION[$storage][$id] -=1 : $_SESSION[$storage][$id] = 0;
            if($_SESSION[$storage][$id] < 1){
                unset($_SESSION[$storage][$id]);
            }
        }

        return $response->withRedirect('/THBeats/public/cart');
    }

    public function storageExists($storage){
        if(isset($_SESSION[$storage])){
            if(sizeof($_SESSION[$storage])){
                return true;
            }
        }
        return false;
    }

    public function inStorage($storage,$id){
        if(isset($_SESSION[$storage])){
            if(isset($_SESSION[$storage][$id])){
                return true;
            }
        }
        return false;
    }

    public function deleteStorage($storage){
        unset($_SESSION[$storage]);
    }

}