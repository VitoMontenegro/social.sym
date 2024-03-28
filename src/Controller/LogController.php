<?php

namespace App\Controller;
use App\Entity\Devices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class LogController extends AbstractController
{

    #[Route('/company_reg', name: 'company_reg_dir')]
    public function company_reg(EntityManagerInterface $entityManager): Response
    {
        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $post['server'] = Request::createFromGlobals()->getHost();
        $auth = (int)json_decode($post['userid'],true)['id']!==0;
        return $this->json(['caption' => 'company_reg','auth'=>$auth]);
    }

    #[Route('/home', name: 'home_dir')]
    public function home(EntityManagerInterface $entityManager): Response
    {
        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $post['server'] = Request::createFromGlobals()->getHost();
        $auth = (int)json_decode($post['userid'],true)['id']!==0;
        return $this->json(['caption' => 'HOME PAGE','auth'=>$auth]);
    }

    #[Route('/login', name: 'login_dir')]
    public function login(EntityManagerInterface $entityManager): Response
    {
        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $post['server'] = Request::createFromGlobals()->getHost();
        $auth = (int)json_decode($post['userid'],true)['id']!==0;
        return $this->json(['caption' => 'LOGIN PAGE','auth'=>$auth]);
    }

    #[Route('/reg_account_choose', name: 'reg_account_choose_dir')]
    public function reg_account_choose(EntityManagerInterface $entityManager): Response
    {
        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $post['server'] = Request::createFromGlobals()->getHost();
        $auth = (int)json_decode($post['userid'],true)['id']!==0;
        return $this->json(['caption' => 'reg_account_choose','auth'=>$auth]);
    }

    #[Route('/remember', name: 'remember_dir')]
    public function remember(EntityManagerInterface $entityManager): Response
    {
        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $post['server'] = Request::createFromGlobals()->getHost();
        $auth = (int)json_decode($post['userid'],true)['id']!==0;
        return $this->json(['caption' => 'remember','auth'=>$auth]);
    }

    #[Route('/user_reg', name: 'user_reg_dir')]
    public function user_reg(EntityManagerInterface $entityManager): Response
    {
        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $post['server'] = Request::createFromGlobals()->getHost();
        $auth = (int)json_decode($post['userid'],true)['id']!==0;
        return $this->json(['caption' => 'user_reg','auth'=>$auth]);
    }

    #[Route('/user', name: 'user_comp')]
    public function user(EntityManagerInterface $entityManager): Response
    {
        return $this->json(['caption' => 'USER COMPONENT']);
    }
}
