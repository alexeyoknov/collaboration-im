<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class DefaultController extends AbstractController
{
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $cr = $em->getRepository('App:Category');

        $categories = $cr->findAll();
        //\var_dump($categories); exit;

        return $this->render('default/index.html.twig', [
            'categories' => $categories
        ]);
    }

    public function category(int $id, EntityManagerInterface $em)
    {
        
        $category = $em->getRepository('App:Category')->findOneBy(['id'=>$id,'active'=>true],null,1);

        return $this->render('default/category.html.twig', [
            'category'=>$category
        ]);
    }

    public function product(int $id, EntityManagerInterface $em)
    {
        
        $product = $em->getRepository('App:Product')->find($id);

        return $this->render('default/product.html.twig', [
            'product'=>$product
        ]);
    }

    public function getProducts(int $categoryId, EntityManagerInterface $em)
    {
        
        $products = $em->getRepository('App:Category')->findOneBy(['id'=>$categoryId]);

        return $this->render('default/products.html.twig', [
            'products'=>$products->getProducts()
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

    public function getCategoriesPath($id=null, $isProductPage=false)
    {
        $path = [];
        $em = $this->getDoctrine()->getManager();
        $current = $em->getRepository('App:Category')->findOneBy([
            //'active'=>true,
            'id'=>$id
        ]);

        if ($isProductPage) {
            $url = $this->generateUrl('category',['id'=>$current->getId()]);
            $path[] = "<a href=$url >".(string) $current . "</a>";
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
                array_unshift($path,"<a href=$url >".(string) $current . "</a>");
            }
        }  
        
        return new Response(implode(' / ', $path) . " /");
    } 
        
}