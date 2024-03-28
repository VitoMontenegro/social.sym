<?php

namespace App\Controller;


use App\Entity\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class EmailController extends AbstractController
{

    #[Route('/check_email', name: 'check_email')]
    public function check_email(EntityManagerInterface $entityManager): Response
    {
        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $code = $post['param']['code'];
        $email = $post['param']['email'];
        $Repository = $entityManager->getRepository(Email::class);
        $isCode = $Repository->isCode($code,$email);
        return $this->json($isCode);
    }

    #[Route('/confirm_email', name: 'confirm_email')]
    public function confirm_email(EntityManagerInterface $entityManager): Response
    {
        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $post['server'] = Request::createFromGlobals()->getHost();
        $email = $post['param']['email'];
        $userid = json_decode($post['userid'],true)['id'];
        $code = $post['param']['code'];
        $Repository = $entityManager->getRepository(Email::class);
        $resp = $Repository->confirmEmail($email,$code,$userid);
        return $this->json($resp);
    }

    #[Route('/add_email', name: 'add_email')]
    public function add_email(EntityManagerInterface $entityManager): Response
    {
        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $email = $post['param']['email'];
        $Repository = $entityManager->getRepository(Email::class);
        $resp = $Repository->addEmail($email);
        return $this->json($resp);
    }

    #[Route('/add_reset_email', name: 'add_reset_email')]
    public function add_reset_email(EntityManagerInterface $entityManager): Response
    {
        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $email = $post['param']['email'];
//        $dump = var_export($resp->getId(), true);
//        file_put_contents(__DIR__ . '/log.txt', $dump . PHP_EOL, FILE_APPEND);
        $Repository = $entityManager->getRepository(Email::class);
        $resp = $Repository->addResetEmail($email);



        return $this->json($resp);
    }

    #[Route('/check_reset_email', name: 'check_reset_email')]
    public function check_reset_email(EntityManagerInterface $entityManager): Response
    {
        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $code = $post['param']['code'];
        $email = $post['param']['email'];
        $Repository = $entityManager->getRepository(Email::class);
        $isCode = $Repository->isResetCode($code,$email);

        return $this->json($isCode);
    }


}
