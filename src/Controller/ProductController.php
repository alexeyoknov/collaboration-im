<?php

namespace App\Controller;

use App\Form\AddToCartType;
use App\Manager\CartManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    const PAGE_NOT_EXIST = 'default/page-not-exist.html.twig';

    public function product(int $id, EntityManagerInterface $em, Request $request, CartManager $cartManager): Response
    {

        $product = $em->getRepository('App:Product')->find($id);
        $form = $this->createForm(AddToCartType::class);

        $form = $this->createForm(AddToCartType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $item->setProduct($product);

            $cart = $cartManager->getCurrentCart();
            $cart
                ->addOrderProduct($item)
                ->setUpdatedAt(new \DateTime());

            $cartManager->save($cart);

            return $this->redirectToRoute('product.detail', ['id' => $product->getId()]);
        }
        
        return $this->render(
            ($product ? 'default/product.html.twig' : self::PAGE_NOT_EXIST), [
            'product' => $product,
            'id' => $id,
            'type' => 'Product',
            'form' => $form->createView()
        ]);
    }
}
