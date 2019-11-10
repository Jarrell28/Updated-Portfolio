<?php
/**
 * Created by PhpStorm.
 * User: jhous
 * Date: 12/23/2018
 * Time: 12:32 PM
 */

namespace App\Controllers;

use Exception;
use Slim\Views\Twig;
use Illuminate\Database\Query\Builder;
use Slim\Flash\Messages;

class ServiceController
{
    protected $view;
    protected $flash;
    protected $service;

    public function __construct(Twig $view, Messages $flash, Builder $service)
    {
        $this->view = $view;
        $this->flash = $flash;
        $this->service = $service;
    }

    public function index($request,$response){

        if(sizeof($this->flash->getMessages())) {
            $message = $this->flash->getMessages();
            return $this->view->render($response, 'service/index.twig', [ 'message' => $message['Message'][0]]);
        }

        return $this->view->render($response, 'service/index.twig');
    }

    public function service($request, $response){
        if(isset($_POST['service'])){
            $plan = $_POST['service-plan'];
            $name = trim($_POST['service-name']);
            $email = trim($_POST['service-email']);
            $message = trim($_POST['service-message']);
            $path = PUBROOT . DIRECTORY_SEPARATOR . "serviceFiles";
            $file = $_FILES['service-file']["name"];
            $tmp_name = $_FILES['service-file']["tmp_name"];


            try{
                if(!$this->service->insert(['email' => $email, 'message' => $message, 'file' => $file, 'plan' => $plan, 'name' => $name])){
                    throw new Exception();
                };
                move_uploaded_file($tmp_name, $path . DIRECTORY_SEPARATOR . $file);
                $this->flash->addMessage('Message', '<div class="alert alert-success">Successfully Submitted! We will contact you soon!</div>');
            } catch (\Exception $e){
                $this->flash->addMessage('Message', '<div class="alert alert-danger">An error has occurred!</div>');
            }
        }
        return $response->withRedirect('/THBeats/public/services');
    }

}
