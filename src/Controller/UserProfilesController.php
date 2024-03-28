<?php

namespace App\Controller;

use App\Entity\UserProfiles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class UserProfilesController extends AbstractController
{
    #[Route('/user_profile', name: 'user_profile')]
    public function user(EntityManagerInterface $entityManager): Response
    {

        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $post['server'] = Request::createFromGlobals()->getHost();
        $userid = json_decode($post['userid'],true)['id'];
        $Repository = $entityManager->getRepository(UserProfiles::class);
        $resp = $Repository->getProfile($userid);
        return $this->json($resp);
    }

    #[Route('/user_registration', name: 'user_registration')]
    public function registration(EntityManagerInterface $entityManager): Response
    {
        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $post['server'] = Request::createFromGlobals()->getHost();
        $userid = json_decode($post['userid'],true)['id'];
        $Repository = $entityManager->getRepository(UserProfiles::class);
        $resp = $Repository->addProfile($post['param'],$userid);
        return $this->json($resp);
    }
}
