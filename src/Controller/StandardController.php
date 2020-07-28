<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StandardController extends AbstractController
{
    /**
     * @Route("/register/user", options={"expose"=true}, name="register_user")
     */
    public function register_user(){
        return new JsonResponse(['success'=>true]);
    }
}
