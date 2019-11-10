<?php
/**
 * Created by PhpStorm.
 * User: jhous
 * Date: 12/23/2018
 * Time: 12:32 PM
 */

namespace App\Controllers;

use Slim\Views\Twig;
use Illuminate\Database\Query\Builder;
use Slim\Flash\Messages;

class HomeController
{
    protected $view;
    protected $songs;
    protected $messages;
    protected $flash;

    public function __construct(Twig $view, Builder $songs, Builder $messages, Messages $flash)
    {
        $this->view = $view;
        $this->songs = $songs;
        $this->messages = $messages;
        $this->flash = $flash;

    }

    public function home($request,$response){

        $songs = $this->songs->get();

        $songArr = array();
        foreach($songs as $song){
            array_push($songArr, "music/" . $song->song_file);
        }

        if(sizeof($this->flash->getMessages())) {
            $message = $this->flash->getMessages();
            return $this->view->render($response, 'home/index.twig', ['songs' => $songs, 'songArr' => $songArr, 'message' => $message['Message'][0]]);
        }

        return $this->view->render($response, 'home/index.twig', ['songs' => $songs, 'songArr' => $songArr]);
    }

    public function contact($request, $response){
        if(isset($_POST['contact-submit'])){
            $name = trim($_POST['contact-name']);
            $email = trim($_POST['contact-email']);
            $subject = trim($_POST['contact-subject']);
            $message = trim($_POST['contact-message']);

            try{
                $this->messages->insert(['email' => $email, 'subject' => $subject, 'message' => $message]);
                $this->flash->addMessage('Message', '<div class="text-center text-white mt-4">Email Successfully Sent</div>');
            } catch (\Exception $e){
                $this->flash->addMessage('Message', '<div class="text-center text-white mt-4">Error occurred while sending email!</div>');
            }



        }
        return $response->withRedirect('/THBeats/public/#contact');
    }
}
