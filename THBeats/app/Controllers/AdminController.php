<?php
/**
 * Created by PhpStorm.
 * User: jhous
 * Date: 12/23/2018
 * Time: 12:32 PM
 */

namespace App\Controllers;

use Exception;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use Illuminate\Database\Query\Builder;

class AdminController
{
    protected $view;
    protected $songs;
    protected $kits;
    protected $flash;
    protected $messages;
    protected $services;
    protected $orders;

    public function __construct(Twig $view, Builder $songs, Builder $kits, Messages $flash, Builder $messages, Builder $services, Builder $orders)
    {
        $this->view = $view;
        $this->songs = $songs;
        $this->kits = $kits;
        $this->flash = $flash;
        $this->messages = $messages;
        $this->services = $services;
        $this->orders = $orders;

    }

    public function index($request,$response){

        $data['songs'] = $this->songs->get()->count();
        $data['kits'] = $this->kits->get()->count();
        $data['messages'] = $this->messages->get()->count();
        $data['orders'] = $this->orders->get()->count();

        return $this->view->render($response, 'admin/dashboard/dashboard.twig', ['data' => $data]);
    }

    public function songView($request,$response){

        $songs = $this->songs->get();

        if(sizeof($this->flash->getMessages())) {
            $message = $this->flash->getMessages();
            return $this->view->render($response, 'admin/songs/songs.twig', ['songs' => $songs, 'message' => $message['Message'][0]]);
        }

        return $this->view->render($response, 'admin/songs/songs.twig', ['songs' => $songs]);
    }

    public function addSongView($request, $response){
        return $this->view->render($response, 'admin/songs/addSong.twig');
    }

    public function addSong($request, $response){
        $title = trim($_POST['song-title']);
        $price = trim($_POST['song-price']);
        $duration = trim($_POST['song-duration']);
        $path = PUBROOT . DIRECTORY_SEPARATOR . "music";
        $file = $_FILES['song-file']["name"];
        $tmp_name = $_FILES['song-file']["tmp_name"];

        try{
            if(!$this->songs->insert(['Title' => $title, 'Price' => $price, 'song_file' => $file, 'Duration' => $duration])){
                throw new Exception();
            };
            move_uploaded_file($tmp_name, $path . DIRECTORY_SEPARATOR . $file);
            $this->flash->addMessage('Message', '<div class="alert alert-success">Successfully Added Song!</div>');
        } catch (\Exception $e){
            $this->flash->addMessage('Message', '<div class="alert alert-danger">An error has occurred!</div>');
        }

        return $response->withRedirect('/THBeats/public/admin/songs');

    }

    public function editSongView($request, $response, $args){
        $id = $args['id'];

        $song = $this->songs->where('id', $id)->first();

        return $this->view->render($response, 'admin/songs/editSong.twig', ['song' => $song]);
    }

    public function editSong($request, $response, $args){
        $id = $args['id'];

        $song = $this->songs->where('id', $id)->first();

        $this->songs->where('id', $id)->update(['Title' => trim($_POST['song-title']), 'Price' => trim($_POST['song-price']), 'Duration' => trim($_POST['song-duration'])]);

        if(is_uploaded_file($_FILES['song-file']["tmp_name"])){
            $path = PUBROOT . DIRECTORY_SEPARATOR . "music";
            $songfile = $song->song_file;
            unlink($path . DIRECTORY_SEPARATOR . $songfile);

            $file = trim($_FILES['song-file']["name"]);
            $tmp_name = trim($_FILES['song-file']["tmp_name"]);

            try{
                if(!$this->songs->where('id', $id)->update(['song_file' => trim($file)])){
                    throw new Exception();
                }
                move_uploaded_file($tmp_name, $path . DIRECTORY_SEPARATOR . $file);

            } catch (\Exception $e){
                $this->flash->addMessage('Message', '<div class="alert alert-danger">Error occurred while uploading file!</div>');
            }
        }

        $this->flash->addMessage('Message', '<div class="alert alert-success">Successfully edited song!</div>');

        return $response->withRedirect('/THBeats/public/admin/songs');
    }

    public function deleteSong($request, $response, $args){
        $id = $args['id'];
        $path = PUBROOT . DIRECTORY_SEPARATOR . "music";
        $file = $this->songs->select('song_file')->where('id', $id)->first();

        try{
            unlink($path . DIRECTORY_SEPARATOR . $file->song_file);
            $this->songs->where('id', $id)->delete();
            $this->flash->addMessage('Message', '<div class="alert alert-success">Successfully Deleted Song!</div>');
        } catch(\Exception $e){
            $this->flash->addMessage('Message', '<div class="alert alert-danger">An error has occurred!</div>');
        }

        return $response->withRedirect('/THBeats/public/admin/songs');
    }

    public function kitView($request,$response){

        $kits = $this->kits->get();

        if(sizeof($this->flash->getMessages())) {
            $message = $this->flash->getMessages();
            return $this->view->render($response, 'admin/kits/kits.twig', ['kits' => $kits, 'message' => $message['Message'][0]]);
        }

        return $this->view->render($response, 'admin/kits/kits.twig', ['kits' => $kits]);
    }

    public function addKitView($request, $response){
        return $this->view->render($response, 'admin/kits/addKit.twig');
    }

    public function addKit($request, $response){
        $title = trim($_POST['kit-title']);
        $price = trim($_POST['kit-price']);
        $description = trim($_POST['kit-description']);
        $path = PUBROOT . DIRECTORY_SEPARATOR . "img";
        $file = $_FILES['kit-image']["name"];
        $tmp_name = $_FILES['kit-image']["tmp_name"];

        try{
            if(!$this->kits->insert(['kit_title' => $title, 'kit_price' => $price, 'kit_image' => $file, 'kit_description' => $description])){
                throw new Exception();
            };
            move_uploaded_file($tmp_name, $path . DIRECTORY_SEPARATOR . $file);
            $this->flash->addMessage('Message', '<div class="alert alert-success">Successfully Added Kit!</div>');
        } catch (\Exception $e){
            $this->flash->addMessage('Message', '<div class="alert alert-danger">An error has occurred!</div>');
        }

        return $response->withRedirect('/THBeats/public/admin/kits');

    }

    public function editKitView($request, $response, $args){
        $id = $args['id'];

        $kit = $this->kits->where('id', $id)->first();

        return $this->view->render($response, 'admin/kits/editKit.twig', ['kit' => $kit]);
    }

    public function editKit($request, $response, $args){
        $id = $args['id'];

        $kit = $this->kits->where('id', $id)->first();

        try{

        $this->kits->where('id', $id)->update(['kit_title' => trim($_POST['kit-title']), 'kit_price' => trim($_POST['kit-price']), 'kit_description' => trim($_POST['kit-description'])]);

        $this->flash->addMessage('Message', '<div class="alert alert-success">Successfully edited kit!</div>');

        if(is_uploaded_file($_FILES['kit-image']["tmp_name"])) {
            $path = PUBROOT . DIRECTORY_SEPARATOR . "img";
            $kitfile = $kit->kit_image;
            unlink($path . DIRECTORY_SEPARATOR . $kitfile);

            $file = trim($_FILES['kit-image']["name"]);
            $tmp_name = trim($_FILES['kit-image']["tmp_name"]);

            if (!$this->kits->where('id', $id)->update(['kit_image' => trim($file)])) {
                throw new Exception();
            }
            move_uploaded_file($tmp_name, $path . DIRECTORY_SEPARATOR . $file);
        }

            } catch (\Exception $e){
                $this->flash->addMessage('Message', '<div class="alert alert-danger">Error has occurred!</div>');
            }


        return $response->withRedirect('/THBeats/public/admin/kits');
    }

    public function deleteKit($request, $response, $args){
        $id = $args['id'];
        $path = PUBROOT . DIRECTORY_SEPARATOR . "img";
        $file = $this->kits->select('kit_image')->where('id', $id)->first();

        try{
            unlink($path . DIRECTORY_SEPARATOR . $file->kit_image);
            $this->kits->where('id', $id)->delete();
            $this->flash->addMessage('Message', '<div class="alert alert-success">Successfully Deleted Song!</div>');
        } catch(\Exception $e){
            $this->flash->addMessage('Message', '<div class="alert alert-danger">An error has occurred!</div>');
        }

        return $response->withRedirect('/THBeats/public/admin/kits');
    }

    public function messageView($request, $response){
        $messages = $this->messages->get();

        if(sizeof($this->flash->getMessages())) {
            $message = $this->flash->getMessages();
            return $this->view->render($response, 'admin/messages/messages.twig', ['messages' => $messages, 'message' => $message['Message'][0]]);
        }

        return $this->view->render($response, 'admin/messages/messages.twig', ['messages' => $messages]);
    }

    public function viewMessage($request, $response, $args){
        $id = $args['id'];

        $message = $this->messages->where('id', $id)->first();

        return $this->view->render($response, 'admin/messages/viewMessage.twig', ['message' => $message]);
    }

    public function deleteMessage($request, $response, $args){
        $id = $args['id'];

        if($this->messages->where('id', $id)->delete()){
            $this->flash->addMessage('Message', '<div class="alert alert-success">Successfully Deleted Message!</div>');
        } else {
            $this->flash->addMessage('Message', '<div class="alert alert-danger">Error occurred while deleting message!</div>');
        };

        return $response->withRedirect('/THBeats/public/admin/messages');
    }

    public function serviceView($request, $response){
        $services = $this->services->get();

        if(sizeof($this->flash->getMessages())) {
            $message = $this->flash->getMessages();
            return $this->view->render($response, '/admin/services/services.twig', ['services' => $services, 'message' => $message['Message'][0]]);
        }

        return $this->view->render($response, '/admin/services/services.twig', ['services' => $services]);

    }

    public function viewService($request, $response, $args){
        $id = $args['id'];

        $service = $this->services->where('id', $id)->first();

        return $this->view->render($response, '/admin/services/viewService.twig', ['service' => $service]);
    }

    public function deleteService($request, $response, $args){
        $id = $args['id'];

        if($this->services->where('id', $id)->delete()){
            $this->flash->addMessage('Message', '<div class="alert alert-success">Successfully Deleted Service!</div>');
        } else {
            $this->flash->addMessage('Message', '<div class="alert alert-danger">Error occurred while deleting service!</div>');
        };

        return $response->withRedirect('/THBeats/public/admin/services');
    }

    public function orderView($request, $response){
        $orders = $this->orders->get();

        if(sizeof($this->flash->getMessages())) {
            $message = $this->flash->getMessages();
            return $this->view->render($response, '/admin/orders/orders.twig', ['orders' => $orders, 'message' => $message['Message'][0]]);
        }

        return $this->view->render($response, '/admin/orders/orders.twig', ['orders' => $orders]);

    }

    public function deleteOrder($request, $response, $args){
        $id = $args['id'];

        if($this->orders->where('id', $id)->delete()){
            $this->flash->addMessage('Message', '<div class="alert alert-success">Successfully Deleted Order!</div>');
        } else {
            $this->flash->addMessage('Message', '<div class="alert alert-danger">Error occurred while deleting order!</div>');
        };

        return $response->withRedirect('/THBeats/public/admin/orders');
    }
}
