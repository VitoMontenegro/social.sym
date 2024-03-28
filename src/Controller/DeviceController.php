<?php

namespace App\Controller;
use App\Entity\Devices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class DeviceController extends AbstractController
{

    #[Route('/device', name: 'check_device')]
    public function addDevice(EntityManagerInterface $entityManager): Response
    {

        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $userid = $entityManager->getRepository(Devices::class)->getDevice($post);
        return $this->json(array('id' => $userid));


    }

    #[Route('/auth_device', name: 'auth_device')]
    public function auth(EntityManagerInterface $entityManager): Response
    {
        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $post['server'] = Request::createFromGlobals()->getHost();
        //$Repository = $entityManager->getRepository(Devices::class);
        //$login = $Repository->login('https://auth.smssystems.ru/login',$post);

        return $this->json($post);
    }

    #[Route('/add_device', name: 'add_device')]
    public function add_device(EntityManagerInterface $entityManager): Response
    {
        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $Repository = $entityManager->getRepository(Devices::class);
        $login = $Repository->profile('https://auth.smssystems.ru/profile',$post);

        return $this->json($login);
    }

    #[Route('/remove_device', name: 'remove_device')]
    public function remove_device(EntityManagerInterface $entityManager): Response
    {
        $post = json_decode(Request::createFromGlobals()->getContent(),true);
        $Repository = $entityManager->getRepository(Devices::class);
        $resp = $Repository->delete_profile($post);
        return $this->json($resp);
    }
}
