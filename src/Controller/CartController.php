<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class CartController extends AbstractController
{
    const CART_STEPS = [
        0 => 'cart',
        1 => 'checkout',
        2 => 'pay'
    ];

    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $step = $request->get('step');
        $progress = isset($step)
            ? (in_array($step,self::CART_STEPS) ? $step : 'cart')
            : 'cart';
        //cart => checkout => pay
        return $this->render('default/cart.html.twig',[
            'title' => 'Home || Clothing',
            'progress' => $progress 
        ]);
    }
}