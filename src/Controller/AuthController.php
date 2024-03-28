<?php

namespace App\Controller;

use App\Entity\Auth;
use App\Entity\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class AuthController extends AbstractController
{

    #[Route('/config', name: 'config')]
    public function config(EntityManagerInterface $entityManager): Response
    {
        $post = json_decode(Request::createFromGlobals()->getContent(), true);
        $auth = (int)json_decode($post['userid'],true)['id']!==0;
        return $this->json(['auth'=>$auth]);
    }

    #[Route('/add_auth', name: 'add_auth')]
    public function add_auth(EntityManagerInterface $entityManager): Response
    {
        $post = json_decode(Request::createFromGlobals()->getContent(), true);
        $Repository = $entityManager->getRepository(Auth::class);
        $resp = $Repository->addAuth($post['param'],$post['uuid']);
        return $this->json($resp);
    }

    #[Route('/login_by_email', name: 'login_check_email')]
    public function login_check_email(EntityManagerInterface $entityManager): Response {
        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $email = $post['value'];
        $password = $post['password'];
        $Repository = $entityManager->getRepository(Email::class);
        $isMail = $Repository->getMail($email);
        $Repository = $entityManager->getRepository(Auth::class);
        $resp = $Repository->checkAuth($isMail,$password,$post['uuid']);
//        $dump = var_export($resp, true);
//        file_put_contents(__DIR__ . '/log.txt', $dump . PHP_EOL, FILE_APPEND);
        return $this->json($resp);
    }

    #[Route('/change_pass', name: 'change_pass')]
    public function change_pass(EntityManagerInterface $entityManager): Response
    {
        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $email = $post['param']['email'] ?? '';
        $mail_code = $post['param']['mail_code'] ?? '';
        $password = $post['param']['password'] ?? '';

        $Repository = $entityManager->getRepository(Email::class);
        $resp = $Repository->checkMailReset($mail_code, $email);

        //$dump = var_export($resp, true);
        //file_put_contents(__DIR__ . '/log.txt', $dump . PHP_EOL, FILE_APPEND);

        $Repository = $entityManager->getRepository(Auth::class);
        $resp = $Repository->resetAuth($resp,$password);
        return $this->json($resp);

    }

}
