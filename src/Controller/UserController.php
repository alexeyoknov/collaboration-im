<?php

namespace App\Controller;

use App\Entity\SonataUserUser;
use App\Form\LoginFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class UserController extends AbstractController
{
    /**
     * @Route("/userprofile", name="app_userprofile")
     */
    public function userProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
 
        return $this->render('default/layouts/user-profile.html.twig', [
            'userData' => [],
        ]);
    }
    
}