<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class MailerController extends AbstractController
{
    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $recipient = $post['email'];
        $code= $post['code'];
        $email = (new Email())
                ->from('viataliy060282@gmail.com')
                ->to($recipient)
                ->subject('confirmation code: '.$code)
                ->text('confirmation code: '.$code)
                ->html('<p>confirmation code: '.$code.'</p>');
                $mailer->send($email);
            return $this->json(array('success'=>true,'code'=>$code));
    }
}