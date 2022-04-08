<?php
namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Product;
use App\Entity\SonataUserUser;
use App\Form\CartType;
use App\Manager\CartManager;
use App\Storage\CartSessionStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    const CART_STEPS = [
        0 => 'cart',
        1 => 'checkout',
        2 => 'pay'
    ];
    
    private ?SonataUserUser $user = null;

    private function getCurrenUser(): ?SonataUserUser
    {
        //$token = $this->get('security.token_storage')->getToken();
        $this->user = $this->getUser();
        return $this->user;
    }
    
    private function checkUserCart(CartManager $cartManager)
    {
        if (null !== $this->getCurrenUser()) {
            $order = $this->user->getUnfinishedOrder();
            if (null !== $order) {
                if ($order->getId() !== $cartManager->getCurrentCart()->getId())
                    $cartManager->setCart($order);
            } else {
                $cartManager->getCurrentCart()->setUser($this->user);
                $cartManager->save($cartManager->getCurrentCart());
            }
        }
    }
    
    public function index(Request $request, CartManager $cartManager): Response
    {        
        $this->checkUserCart($cartManager);
        
        $cart = $cartManager->getCurrentCart();
        
        $form = $this->createForm(CartType::class, $cart);
        
        $step = $request->get('step');
        $cartRequest = $request->get('cart');
        
        if (null !== $cartRequest) {
            $cartRequestActionClear = array_key_exists('clear', $cartRequest);
            $cartRequestActionSave = array_key_exists('save', $cartRequest);
                   
            if (true === $cartRequestActionClear) {
                $cart->removeOrderProducts();
                $cartManager->save($cart);
                return $this->redirectToRoute('cart',['step'=>$step]);
            }
            
            $orderProducts = $cart->getOrderProducts();
            if (false === $cartRequestActionSave) {
                foreach ($cartRequest['orderProducts'] as $key => $orderProduct)
                    if (true === array_key_exists('remove', $orderProduct)) {
                        $cart->removeOrderProduct($orderProducts[$key]);
                        $cartManager->save($cart);
                        return $this->redirectToRoute('cart',['step'=>$step]);
                    } 
            } else {
                $em = $this->getDoctrine()->getManager();
                $changed = false;
                foreach ($cartRequest['orderProducts'] as $key => $orderProduct)
                    if ($orderProducts[$key]->getQuantity() !== $orderProduct['quantity']) {
                        $orderProducts[$key]->setQuantity($orderProduct['quantity']);
                        $em->persist($orderProducts[$key]);
                        $em->flush();
                        $changed = true;
                    }
                if (true === $changed)
                    return $this->redirectToRoute('cart',['step'=>$step]);
            }
        }
        $progress = isset($step)
            ? (in_array($step,self::CART_STEPS) ? $step : 'cart')
            : 'cart';

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $cart->setUpdatedAt(new \DateTime());
            $cartManager->save($cart);
            
            return $this->redirectToRoute('cart',['step'=>$step]);
        }
        
        //cart => checkout => pay
        return $this->render('default/cart.html.twig',[
            'title' => 'Home || Clothing: cart - step ' . $step,
            'progress' => $progress,
            'cart' => $cart,
            //'req' => var_export($cartRequest),
            'form' => $form->createView()
        ]);
    }

    /**
     * 
     * @Route("/cart/remove/{id}", name="cart_remove_item", methods={"POST"})
     */
    public function removeItem(int $id, Request $request, CartManager $cartManager, bool $responseTotal = true)
    {
        $cart = $cartManager->getCurrentCart();
        $orderProducts = $cart->getOrderProducts();
        foreach ($orderProducts as $orderProduct)
            if ($orderProduct->getProduct()->getId() === $id)
                $cart->removeOrderProduct($orderProduct);
        $cartManager->save($cart);
        
        if (true === $responseTotal) {
            return new JsonResponse(['total' => $cart->getTotal()]);
        } else
            return $this->render(
                'default/layouts/parts/header-cart.html.twig',
                [
                    'cart' => $cart
                ]
            );
    }

    /**
     *
     * @Route("/cart-product-add/{id}", name="cart_product_add", methods={"POST"})
     */
    public function cartProductAdd(Request $request, CartManager $cartManager)
    {
        $cart = $cartManager->getCurrentCart();
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->findOneBy([
            'id' => $request->get('id'),
        ]);
        
        $orderProduct = $em->getRepository(OrderProduct::class)->findOneBy([
            'OrderRef' => $cart,
            'Product' => $product
        ]);

        if (null === $orderProduct) {

            $this->getCurrenUser();
            
            $orderProduct = new OrderProduct(); // $em->getRepository(OrderProduct::class);
            $orderProduct->setOrderRef($cart);
            $orderProduct->setProductPrice($product->getPrice());
            $orderProduct->setQuantity(1);
            $orderProduct->setProduct($product);

            $cart = $cartManager->getCurrentCart();
            $cart
                ->addOrderProduct($orderProduct)
                ->setUpdatedAt(new \DateTime());

            if (null !== $this->user) {
                $cart->setUser($this->user);
            }
            $cartManager->save($cart);

        } else {
            $orderProduct->setQuantity($orderProduct->getQuantity()+1);
        }
        $em->persist($orderProduct);
        $em->flush();
  
//        $cartManager->save($cart);
//        $cart = $cartManager->getCurrentCart();
        
        //return  new JsonResponse(['id'=>$request->get('id')]);
        
        return new JsonResponse(
            [
                'response'=>$this->renderView(
            'default/layouts/parts/header-cart.html.twig',
                    [
                    'cart' => $cart
                    ]
                ),
        ]);
    }

    /**
     * @param Request $request
     * @param CartManager $cartManager
     * @return Response
     */
    public function showCart(Request $request, CartManager $cartManager): Response
    {
        $this->checkUserCart($cartManager);
        $cart = $cartManager->getCurrentCart();
        
        return $this->render(
            'default/layouts/parts/header-cart.html.twig',
            [
                'cart' => $cart
            ]
        );
    }
}