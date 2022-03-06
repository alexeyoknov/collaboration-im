<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class CartController extends AbstractController
{

    public function index(EntityManagerInterface $em, Request $request): Response
    {

        return $this->render('default/cart.html.twig',[
            'title' => 'Home || Clothing'
        ]);
    }
}