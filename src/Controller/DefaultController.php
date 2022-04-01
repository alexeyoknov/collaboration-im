<?php
namespace App\Controller;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Node\Expression\Test\NullTest;

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

    /**
     * Get Category with id or first category if id is null
     *
     * @param mixed $id int or null
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function category($id = null, EntityManagerInterface $em, Request $request): Response
    {
        $fields = ['id'=>$id, 'active'=>true];
        if (is_null($id))     
            unset($fields['id']);

        $category = $em->getRepository('App:Category')->findOneBy($fields,null,1);

        return $this->render(
            ($category ? 'default/category.html.twig' : self::PAGE_NOT_EXIST), [
            'category' => $category,
            'id' => $id,
            'type' => 'Category',
            'viewType' => $request->get('viewType','grid'),
            'itemsPerPage' =>  $request->get('itemsPerPage',25),
            'currentPage' => $request->get('currentPage',1),
            'orderBy' => $request->get('orderBy','id'),
            'orderType' => $request->get('orderType','ASC')    
        ]);
    }
    

    public function getProducts(int $categoryId, EntityManagerInterface $em): Response
    {
        
        $products = $em->getRepository('App:Category')->findOneBy(['id'=>$categoryId]);

        return $this->render('default/products.html.twig', [
            'products'=>$products->getProducts()
        ]);
    }
    
    /**
     * Get all products in category
     * 
     * @param int|null $categoryId Category id
     * @param string $view Twig file
     * @param int $offset Page Number
     * @param int $limit Items Per Page
     * @param string $orderBy Order field
     * @param string $orderType Order direction ASC | DESC
     * @return Response
     */
    public function getAllProductsInCategory($categoryId, string $view, int $offset = 0, int $limit = 0, string $orderBy = 'id', string $orderType = 'ASC', string  $viewType='grid'): Response
    {       
        $em = $this->getDoctrine()->getManager();
        
        $curID = [$categoryId];
        //$subcats = [];
        if ((null !== $categoryId) && ($categoryId !== 'all')) {
            $cat = $em->getRepository('App:Category')->findOneBy(['id'=>$categoryId]);
            $subcats = $em->getRepository('App:Category')->getAllSubCategories($categoryId,$cat->getRoot()->getId(),$cat->getLft(),$cat->getRgt());
            $subcats = \array_merge($curID, \array_column($subcats,'id'));
            $productsRes = $em->getRepository('App:Product')
                    ->findAllProductsInCategory($subcats, $offset, $limit, $orderBy, $orderType);
            //int $offset = 0, int $limit = 0, string $orderBy = 'id', string $orderType = 'ASC'
        } else
            {
                $productsRes['query'] = $em->getRepository('App:Product')->findBy(['active'=>true]);
                $productsRes['totalCount'] = count($productsRes['query']);
                if ($limit > 0) {
                    $productsRes['query'] = $em->getRepository('App:Product')->findBy(
                        [
                            'active'=>true
                        ]
                        ,null,
                        $limit, $offset*$limit);
                }
            }

        $result = [
            'products' => $productsRes['query'],
            'limit' => $limit,
            'totalCount' => $productsRes['totalCount'],
            'currentPage' => ($offset+1),
            'showFrom' => ($limit * $offset + 1),
            'viewType' => $viewType,
            'showTo' => ($limit>0 ? ($limit*($offset+1)) : $productsRes['totalCount']),
            'orderBy' => $orderBy,
            'orderType' => $orderType,
            //'subcats' => json_encode($subcats,JSON_UNESCAPED_UNICODE)
        ];

        return $this->render($view, $result);
        
    }

    /**
     * @Route("/all-products", name="all_products", methods={"POST"}) 
     * 
     */
    public function getAllProductsAjax(Request $request): Response
    {
        return $this->getAllProductsInCategory(
            $request->get('categoryId',null),
            $request->get('view',''),
            $request->get('offset',0),
            $request->get('limit',0),
            $request->get('orderBy','id'),
            $request->get('orderType','asc'),
            $request->get('viewType','grid'),
        );
    }
    
    public function getCategories(string $view, $parent=null, int $limit=0, EntityManagerInterface $em)
    {
        $categories = $em->getRepository('App:Category')->findBy(
            ['active'=>true, 'Parent'=>$parent],
            null,
            ($limit===0 ? null : $limit)
        );

        return $this->render($view, [
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

    /**
     * Get Categories Path (breadcrumbs) for category id
     *
     * @param int|null $id Category id
     * @param boolean $isProductPage if true - this is product page
     * @param boolean $addLi if true - add <li> tags
     * @return string
     */
    public function getCategoriesPath($id=null, bool $isProductPage=false, bool $addLi=false)
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
    
    /**
     * Get new products function
     *
     * @param EntityManagerInterface $em
     * @param integer $days The number of days after receipt in which the product must be considered new
     * @return Response
     */
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

    /**
     * Get Random Products function
     *
     * @param EntityManagerInterface $em
     * @param integer $limit Limit of products
     * @return Response
     */
    public function getRandomProducts(EntityManagerInterface $em, int $limit=5): Response
    {
        $products = $em->getRepository('App:Product')->findRandProducts($limit);
        //SELECT * FROM `product` WHERE created BETWEEN NOW() - INTERVAL 2 DAY AND NOW(); 
        return $this->render('default/layouts/parts/single-product.html.twig', [
            'products' => $products,
            'labels' => [
            ]
        ]);
    }

    public function getViewsCount(string $template, int $currentItemsPerPage = 25): Response
    {
        return $this->render($template,[
            'pages' => [5,10,15,20,25],
            'current' => $currentItemsPerPage
        ]
        );
    }

    public function getPagination(string $template='', int $currentPage=1, int $limit=10, int $itemsCount=0): Response
    {
        if ($template === '')
            $template = 'default/layouts/parts/categories/categories-pagination.html.twig';
        
        if ($limit > 0)
            $maxPages = (int) ceil($itemsCount / $limit);
        else
            $maxPages = 1;
        /*
            доработать: показывать, например, первые 3 страницы и последнюю
            при текущей странице 1-3: 1,2,3 ... 10
            для 4 страницы - 2,3,4 ... 10
            для 5 страницы - 3,4,5 ... 10
        */
        $firstPage = ($currentPage < 4) ? 1 : ($currentPage-2);
        return $this->render(
            $template,
            [
                'currentPage' => $currentPage,
                'maxPages' => $maxPages,
                'firstPage' => $firstPage,
                'lastPage' => (($firstPage + 2) < $maxPages ? ($firstPage + 2) : $maxPages),
                'prev' => (($currentPage===1) ? $maxPages : ($currentPage-1)),
                'next' => (($currentPage===$maxPages) ? 1 : ($currentPage+1)),
                'limit' => $limit
            ]
        );
    }

    /**
     * @Route("/category-pagination", name="cat_pagination", methods={"POST"}) 
     * 
     */
    public function getAjaxPagination(Request $request): Response
    {
        return $this->getPagination(
            '',
            $request->get('currentPage',1),
            $request->get('limit',0),
            $request->get('itemsCount',0)
        );
    }

    public function addComment(Request $request): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $view = 'product';
        if ((null !== $request->get('username')) && (null !== $request->get('text'))) {
            $em = $this->getDoctrine()->getManager();
            $pr = $this->getDoctrine()->getManager()
                ->getRepository('App:Product')
                ->findOneBy(['id'=>$request->get('product_id')]);
            $em->getRepository('App:Comment');
            $emC = new Comment;
            $emC->setUserName($request->get('username'));
            $emC->setText($request->get('text'));
            $emC->setRating($request->get('rating'));
            $emC->setProduct($pr);
            //->getRepository('App:Comment')->addComment();
            $em ->persist($emC);
            $em->flush();
        }

        return $this->redirectToRoute($view,['id'=>$request->get('product_id')]);

    }
    
    public function searchAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('search');
        $products = $em->getRepository('App:Product')->findByName($requestString);
        
        $productsData = [];
        if (!$products) {
            $result['products']['error'] = "Товаров не найдено";
        } else {
            foreach ($products as $product) {
                $productsData[$product->getId()] = [
                    'name' => $product->getName(),
                    'url' => $this->generateUrl('product', ['id'=>$product->getId()])
                ];
            }
            $result['products'] = $productsData;
        }
        return new Response(json_encode($result));
    }
}