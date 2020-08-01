<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class StandardController extends AbstractController
{
    /**
     * @Route("/register/user", options={"expose"=true}, name="register_user")
     */
    public function register_user(Request $request, UserPasswordEncoderInterface $passwordEncoder){
        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $user_name = $request->request->get('user_name');
            $user_last_name = $request->request->get('user_last_name');
            $user_birthdate = $request->request->get('user_birthdate');
            $user = $em->getRepository(User::class)->findOneBy(['email'=>$email]);
            if($user == null){
                $user = new User($email, User::ROLE_USER, $user_name, $user_last_name, new \DateTime($user_birthdate));
                $user->setPassword($passwordEncoder->encodePassword($user,$password));
                $em->persist($user);
                $em->flush();
                return new JsonResponse(['success'=>true]);
            }else{
                return new JsonResponse(['success'=>false]);
            }
        }
        throw new \Exception('This is not an ajax call');
    }
}
