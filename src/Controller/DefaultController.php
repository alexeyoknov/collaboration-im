<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class DefaultController extends AbstractController
{
    const PAGE_NOT_EXIST = 'default/page-not-exist.html.twig';

    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $cr = $em->getRepository('App:Category');

        $categories = $cr->findAll();
        //\var_dump($categories); exit;

        return $this->render('default/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    public function category(int $id, EntityManagerInterface $em)
    {        
        $category = $em->getRepository('App:Category')->findOneBy(['id'=>$id,'active'=>true],null,1);

        return $this->render(
            ($category ? 'default/category.html.twig' : self::PAGE_NOT_EXIST), [
            'category' => $category,
            'id' => $id,
            'type' => 'Category'
        ]);
    }

    public function product(int $id, EntityManagerInterface $em)
    {
        
        $product = $em->getRepository('App:Product')->find($id);

        return $this->render(
            ($product ? 'default/product.html.twig' : self::PAGE_NOT_EXIST), [
            'product' => $product,
            'id' => $id,
            'type' => 'Product'
        ]);
    }

    public function getProducts(int $categoryId, EntityManagerInterface $em)
    {
        
        $products = $em->getRepository('App:Category')->findOneBy(['id'=>$categoryId]);

        return $this->render('default/products.html.twig', [
            'products'=>$products->getProducts()
        ]);
    }
    
    public function getAllProductsInCategory(int $categoryId, string $view, EntityManagerInterface $em)
    {
        
        $subcats = $em->getRepository('App:Category')->getAllSubCategories($categoryId);
        $subcats = \array_merge([$categoryId],$subcats);

        $products = $em->getRepository('App:Product')->findAllProductsInCategory($subcats);

        return $this->render($view, [
            'products' => $products
        ]);
        
    }
    
    public function getCategories(EntityManagerInterface $em,$parent=null)
    {
        $categories = $em->getRepository('App:Category')->findBy(['active'=>true, 'Parent'=>$parent]);

        return $this->render('default/leftsidebar.html.twig', [
            'categories' => $categories
        ]);
    }

    public function getSubCategories(EntityManagerInterface $em,$parent=null)
    {
        $categories = $em->getRepository('App:Category')->findBy([
            'active'=>true, 'Parent'=>$parent]);

        return $this->render('default/subcategories.html.twig', [
            'categories' => $categories            
        ]);
    } 

    public function getCategoriesPath($id=null, $isProductPage=false, bool $addLi=false)
    {
        $path = [];
        $liStart = $liEnd = "";
        $separator = " / ";
        if (true === $addLi) {
            $liStart = "<li>";
            $liEnd = "</li>";
            $separator="";
        }
        $em = $this->getDoctrine()->getManager();
        $current = $em->getRepository('App:Category')->findOneBy([
            //'active'=>true,
            'id'=>$id
        ]);

        if ($isProductPage) {
            $url = $this->generateUrl('category',['id'=>$current->getId()]);
            $path[] = $liStart . "<a href=$url >".(string) $current . "</a>" . $liEnd;
        }

        while($current)
        {
            $current = $current->getParent() ? 
                $em->getRepository('App:Category')->findOneBy([
                //'active'=>true,
                'id'=>$current->getParent()
                ])
                : null;

            if ($current)
            {
                $url = $this->generateUrl('category',['id'=>$current->getId()]);
                array_unshift($path,$liStart . "<a href=$url >".(string) $current . "</a>" . $liEnd);
            }
        }  
        
        return new Response(implode($separator, $path) . $separator);
    }
    
    public function getNewArrivalProducts(EntityManagerInterface $em, int $days=30)
    {
        $products = $em->getRepository('App:Product')->findNewProducts($days);
        //SELECT * FROM `product` WHERE created BETWEEN NOW() - INTERVAL 2 DAY AND NOW(); 
        return $this->render('default/layouts/parts/single-product.html.twig', [
            'products' => $products,
            'labels' => [
                ['class'=>'new', 'name'=>'New']
            ]
        ]);
    }

    public function getProductRating(int $product_id, string $addonClass, EntityManagerInterface $em)
    {
        $rating = $em->getRepository('App:Comment')->getAverageRating($product_id);
        //SELECT * FROM `product` WHERE created BETWEEN NOW() - INTERVAL 2 DAY AND NOW();
        return $this->render('default/layouts/parts/rating-stars.html.twig', [
            'rating'=>(count($rating)>0 ? (int)round($rating[0]['avgRate'],0) : 0),
            'addonClass' => $addonClass
        ]); 
    }

    public function getRandomProducts(EntityManagerInterface $em, int $limit=5)
    {
        $products = $em->getRepository('App:Product')->findRandProducts($limit);
        //SELECT * FROM `product` WHERE created BETWEEN NOW() - INTERVAL 2 DAY AND NOW(); 
        return $this->render('default/layouts/parts/single-product.html.twig', [
            'products' => $products,
            'labels' => [
            ]
        ]);
    }
 
}