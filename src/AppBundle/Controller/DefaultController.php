<?php
namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Suscriber;
use AppBundle\Entity\Category;
use AppBundle\Entity\Article;
use AppBundle\Entity\Proformat;
use AppBundle\Entity\LigneProformat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
// +use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends Controller
{
    /**
     * @Route("/essai", name="essai")
    **/
    public function essaiAction(Request $request)
    {
        return $this->render('default/essai.html.twig', [
            
        ]);
    }

    /**
     * @Route("/", name="homepage")
    **/
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('shop');
    }

    // defaults={"cat"=20000}

     /**
     * @Route("/shop/category/view/{rayon}/{cat}", name="shop_by_cat")
    **/
    public function shopByCatAction(Request $request, Category $cat, Category $rayon,$page = 1)
    {
        // if($cat == 0){
        //     $cat = $rayon;
        // }
        $em = $this->getDoctrine()->getManager();            
        $maxArticles = 100;

        $filterForm = $this->createFormBuilder()
                    ->add('category',EntityType::class,[
                        'label' => "Categorie",
                        'required' => false,
                        'class' => Category::class,
                        'choice_label' => 'catname',
                        'query_builder' => function ($er) {
                            return $er->createQueryBuilder('c')
                                ->orderBy('c.catname', 'ASC')
                                ;
                        },   
                    ])
                    ->getForm();  
        $session = $this->container->get('session'); 
        $cart = $session->get('cart');
        // dump($session->get('logged'));
        $articles_count = $this->getDoctrine()
                ->getRepository('AppBundle:Article')
                ->countArticlesTotal();
        
        $pagination = NULL;
        // $pagination = array(
        //     'page' => $page,
        //     'route' => 'shop_by_cat',
        //     'pages_count' => ceil($articles_count / $maxArticles),
        //     'route_params' => array('cat' => $cat->getId(),'cat' => $cat->getId())
        // );
        // $categoryArray = $em->getRepository('AppBundle:Category')->findBy([], ['catname' => 'ASC']);
        $queryRayons = $em->createQueryBuilder();
        $categoryArray = $queryRayons
                        ->select('c')
                        ->from('AppBundle:Category', 'c')
                        ->andwhere("c.id = c.parent")                       
                        ->orderBy('c.catname', 'ASC')
                        ->getQuery()
                        ->getResult()
                    ;
        
        $articles = $em->getRepository('AppBundle:Article')
                ->getListByCat($page, $maxArticles, $cat);
        $loggedUser = $this->getUser();
        // exit;
        $shop = [];
        $nbrCart = $session->get('nbrCart');
        if(!empty($loggedUser)){
            $suscriber = $loggedUser->getSuscriber();
            // dump($suscriber);
            if(!empty($suscriber)){
                $client = $suscriber->getClient();
                // dump($articles); 
                foreach ($articles as $key => $article) {
                    $price = $em->getRepository('AppBundle:Prix')->findBy(['client' => $client->getId()]);

                    $query = $em->createQueryBuilder();
                    $price = $query
                        ->select('p')
                        ->from('AppBundle:Prix', 'p')
                        ->join('p.client', 'c')
                        ->join('p.article', 'a') 
                        ->andwhere(":today BETWEEN p.dateDebut AND p.dateFin ")
                        ->andwhere("p.client = :clientId ")
                        ->andwhere("p.article = :articleId ")
                        ->setParameter('today', new \DateTime())
                        ->setParameter('clientId', $client->getId())
                        ->setParameter('articleId', $article->getId())
                        ->orderBy('p.id', 'DESC')
                        ->setMaxResults(1)
                        ->getQuery()
                        ->getOneOrNullResult()
                    ;
                    // dump($price);
                    // exit;
                    $medias = $em->getRepository('AppBundle:Media')->findBy(['article' => $article->getId()]);
                    $shop[] = array('item' => $article, 'medias' => $medias, 'client' => $client, 'price' => $price );
                }
            // dump($categoryArray);


                // replace this example code with whatever you need
                return $this->render('default/shop-by-cat.html.twig', [
                    'currentRayon' => $rayon,
                    'currentCat' => $cat,
                    'cart' => $cart,
                    'nbrCart' => $nbrCart,
                    'articles' => $shop,
                    'categoryArray' => $categoryArray,
                    'pagination' => $pagination,
                ]);
            }
        }
        
        $shop = [];
        foreach ($articles as $key => $article) {
            $shop[] = array('item' => $article );
        }
        // replace this example code with whatever you need
        return $this->render('default/shop-by-cat.html.twig', [
            'currentRayon' => $rayon,
            'currentCat' => $cat,
            'nbrCart' => $nbrCart,
            'cart' => $cart,
            'articles' => $shop,
            'categoryArray' => $categoryArray,
            'pagination' => $pagination,
        ]);
    }
   
    /**
     * @Route("/account", name="account")
    **/
    public function accountAction(Request $request)
    {
        $session = $this->container->get('session'); 
        $em = $this->getDoctrine()->getManager();   
        $cart = $session->get('cart');
        $nbrCart = $session->get('nbrCart');

        $suscriber = $this->getUser()->getSuscriber();
        $client = $suscriber->getClient();
        $orders = $em->getRepository('AppBundle:Proformat')->findBy(['client' => $client]);
        // dump($cart);
        dump($orders);
        // replace this example code with whatever you need
        return $this->render('default/account.html.twig', [
            'cart' => $cart,
            'nbrCart' => $nbrCart,
            'orders' => $orders

        ]);
    }


    /**
     * @Route("/newsletter/{email}", name="newsletter")
    **/
    public function newsletterAction(Request $request)
    {
        $session = $this->container->get('session'); 
        // $session->clear();
        $cart = $session->get('cart');
        $nbrCart = $session->get('nbrCart');
        // dump($cart);
        // dump($nbrCart);
        // replace this example code with whatever you need
        return $this->render('default/cart.html.twig', [
            'cart' => $cart,
            'nbrCart' => $nbrCart
        ]);
    }
   

    /**
     * @Route("/cart", name="cart")
    **/
    public function cartAction(Request $request)
    {
        $session = $this->container->get('session'); 
        // $session->clear();
        $cart = $session->get('cart');
        $nbrCart = $session->get('nbrCart');
        $cartAmount = $session->get('cartAmount');
        // dump($cart);
        // dump($nbrCart);
        // replace this example code with whatever you need
        return $this->render('default/cart.html.twig', [
            'cart' => $cart,
            'nbrCart' => $nbrCart,
            'cartAmount' => $cartAmount
        ]);
    }
   
   /**
     * @Route("/cart/after/login", name="cart_after_login")
    **/
    public function cartAfterLoginAction(Request $request)
    {
        $session = $this->container->get('session'); 
        // $session->clear();
        $cart = $session->get('cart');
        $nbrCart = $session->get('nbrCart');
        dump($cart); exit;
        // dump($nbrCart);
        // replace this example code with whatever you need
        return $this->render('default/cart.html.twig', [
            'cart' => $cart,
            'nbrCart' => $nbrCart
        ]);
    }
   
   
    
    /**
     * @Route("/shop/add/to/cart/{article}", name="add_to_cart")
     * * @Method({"POST","GET"})
    **/
    public function addToCartAction(Request $request, Article $article)
    {
        
        if (!$request->isXmlHttpRequest()) {
            // return new JsonResponse(array('message' => 'You can\'t access this by this way!'), 400);
        }
        $em = $this->getDoctrine()->getManager();           
        $session = $this->container->get('session'); 
        $nbrCart = $session->get('nbrCart');
        $cartAmount = $session->get('cartAmount');
        $cart = $session->get('cart');
        $loggedUser = $this->getUser();
        // dump( json_encode($cart));
        // dump($cart);
        // $session->remove('cart'); exit;
        $order = isset($cart[$article->getId()]) ? $cart[$article->getId()] : NULL;
        $article = $em->getRepository('AppBundle:Article')->findOneById($article->getId());
        // dump($order);
        if(empty($order)){
            // $order = array('article' => $article, 'nbr' => 1);
            $currNbr = 1;
        }
        else{
            $currNbr = $order['nbr'] + 1;
        }
        $nbrCart++;
        $unityPrice = $this->getUniPrice($article, $loggedUser);
        $order = array('article' => $article, 'nbr' => $currNbr, 'price' =>$unityPrice);
        $cart[$article->getId()] = $order;
        


        $orderAmount = $unityPrice * $currNbr;

        $cartAmount = intval($cartAmount);
        if($cartAmount >= 0){
            $cartAmount+= $unityPrice;
        }

        $session->set('cartAmount', $cartAmount);
        $orderAmount = number_format($orderAmount, 0, ',', ' ');
        $session->set('nbrCart', $nbrCart);
        $session->set('cart', $cart);
        // dump($cart);
        // exit;
        return new JsonResponse(array('nbrCart' => $nbrCart,  'article' => $article->getId(), 'nbrOrder' => $currNbr, 'orderAmount' => $orderAmount , 'cartAmount' => $cartAmount )); exit;
        
    }

    /**
     * @Route("/shop/remove/from/cart/{article}", name="remove_from_cart")
     * * @Method({"POST","GET"})
    **/
    public function removeFromCartAction(Request $request, Article $article)
    {
        
        if (!$request->isXmlHttpRequest()) {
            // return new JsonResponse(array('message' => 'You can\'t access this by this way!'), 400);
        }
        $loggedUser = $this->getUser();
        $session = $this->container->get('session'); 
        $nbrCart = $session->get('nbrCart');
        $cartAmount = $session->get('cartAmount');
        
        $cart = $session->get('cart');
        $order = isset($cart[$article->getId()]) ? $cart[$article->getId()] : NULL;
        if(empty($order)){
            // $order = array('article' => $article, 'nbr' => 0);
            unset($cart[$article->getId()]);
        }
        else{
            $currNbr = $order['nbr'] - 1;
            if($currNbr <= 0 ){
                $currNbr = 1;
            }
            else{
                $nbrCart--;
                if($nbrCart <= 0 ){
                    $nbrCart = 1;
                }
            }
            $order = array('article' => $article, 'nbr' => $currNbr);            
            $cart[$article->getId()] = $order;
            

            $unityPrice = $this->getUniPrice($article, $loggedUser);

            $orderAmount = $unityPrice * $currNbr;

            $cartAmount = intval($cartAmount);
            if($cartAmount >= 0){
                $cartAmount -= $unityPrice;
            }

            $session->set('cartAmount', $cartAmount);
            $orderAmount = number_format($orderAmount, 0, ',', ' ');        
            $session->set('cart', $cart);
            $session->set('nbrCart', $nbrCart);
            return new JsonResponse(array('nbrCart' => $nbrCart, 'article' => $article->getId(), 'nbrOrder' => $currNbr, 'orderAmount' => $orderAmount, 'cartAmount' => $cartAmount  )); exit;
        }
        return new JsonResponse(array()); exit;

    }

     /**
     * @Route("/shop/remove/order/{article}", name="remove_order")
     * * @Method({"POST","GET"})
    **/
    public function removeOrderAction(Request $request, Article $article)
    {
        
        if (!$request->isXmlHttpRequest()) {
            // return new JsonResponse(array('message' => 'You can\'t access this by this way!'), 400);
        }


        $loggedUser = $this->getUser();
        $session = $this->container->get('session'); 
        $unityPrice = $this->getUniPrice($article, $loggedUser);



        $nbrCart = $session->get('nbrCart');
        $orderNbr = 0;
        $cart = $session->get('cart');
        $order = isset($cart[$article->getId()]) ? $cart[$article->getId()] : NULL;

        if(empty($order)){
            unset($cart[$article->getId()]);
        }
        else{
            $orderNbr = $order['nbr'];
        }

        $cartAmount = $session->get('cartAmount');
        $cartAmount = intval($cartAmount);
        $orderAmount = $unityPrice * $orderNbr;
        if($cartAmount >= 0){
            $cartAmount -= $orderAmount;
        }

        $nbrCart -= $orderNbr;
        if($nbrCart <0 ){
            $nbrCart = 0;
        }
        unset($cart[$article->getId()]);
        $session->set('cart', $cart);
        $session->set('nbrCart', $nbrCart);
        $session->set('cartAmount', $cartAmount);


       
        return new JsonResponse(array('nbrCart' => $nbrCart, 'article' => $article->getId() )); exit;

    }

    
    /**
     * @Route("/shop/shop/home", name="shop")
    **/
    public function shopAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();            
        $maxArticles = 20;

        $filterForm = $this->createFormBuilder()
                    ->add('category',EntityType::class,[
                        'label' => "Categorie",
                        'required' => false,
                        'class' => Category::class,
                        'choice_label' => 'catname',
                        'query_builder' => function ($er) {
                            return $er->createQueryBuilder('c')
                                ->orderBy('c.catname', 'ASC')
                                ;
                        },   
                    ])
                    ->getForm();  
        $session = $this->container->get('session'); 
        $cart = $session->get('cart');
        $nbrCart = $session->get('nbrCart');
        $query = $em->createQueryBuilder();
        $categoryArray = $query
            ->select('c')
            ->from('AppBundle:Category', 'c')
            ->andwhere("c.id = c.parent")
            ->orderBy('c.catname', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $loggedUser = $this->getUser();
        // replace this example code with whatever you need
        return $this->render('default/shop.html.twig', [
            'cart' => $cart,
            'nbrCart' => $nbrCart,
            'categoryArray' => $categoryArray,
            'filterForm' => $filterForm->createView()
        ]);
    }




    /**
     * @Route("/proforma/{proforma}", name="proforma")
    **/
    public function proformaAction(Request $request, Proformat $proforma)
    {
        $secteur = "Vente de consommables informatiques - Papeteire - Fournitures de bureaux";
        return $this->render('default/proforma.html.twig', [
            'secteur' => $secteur,
            'proforma' => $proforma
        ]);
    }

    /**
     * @Route("/proceed/proforma", name="proceed_proforma")
    **/
    public function proceedProformaAction(Request $request)
    {
        //convertir le panier en proforma
        $session = $this->container->get('session'); 
        $cart = $session->get('cart');
        $nbrCart = $session->get('nbrCart');
        $em = $this->getDoctrine()->getManager();
        $loggedUser = $this->getUser();
        // dump($cart); exit;
        $proforma = new Proformat();
        $proforma->setDateproformat(new \DateTime());
        $suscriber = $this->getUser()->getSuscriber();
        if(!empty($suscriber)){
            $client = $suscriber->getClient();
            $proforma->setClient($client);
        }
        else{
            throw new Exception("Error Processing Request", 1);
        }

        $proforma->setClient($client);
        $totalProforma = 0;
        $proforma->setTotal($totalProforma);
        $em->persist($proforma);
        $em->flush();
        if(empty($cart)){
            exit;
        }
        foreach ($cart as $key => $order) {
            $item = $order['article'];
            $qty = $order['nbr'];
            // dump($loggedUser); exit;
            $item = $em->getRepository('AppBundle:Article')->findOneById($item->getid());
            $unityPrice = $this->getUniPrice($item);
            $totalProforma += $unityPrice * $qty;
            $ligneProforma = new LigneProformat();
            $ligneProforma->setArticle($item);
            $ligneProforma->setPrix($unityPrice);
            $ligneProforma->setQte($qty);
            $ligneProforma->setProformat($proforma);
            $em->persist($ligneProforma);
        }
        $proforma->setTotal($totalProforma);
        $em->persist($proforma);
        $em->flush();
        // exit;
        $session->set('cart', null);
        $session->set('nbrCart', null);

        return $this->redirectToRoute('proforma', array('proforma' => $proforma->getId()));


        return $this->render('default/proforma.html.twig', [
            'secteur' => $secteur
        ]);
    }

    public function getUniPrice($article, $loggedUser = null){
        $loggedUser = $this->getUser();
        $unityPrice = $article->getDefaultPrice();
        if(!empty($loggedUser)){
            $suscriber = $loggedUser->getSuscriber();
            // dump($suscriber);
            if(!empty($suscriber)){
                $client = $suscriber->getClient();
                $cliprice = $this->getDoctrine()->getRepository('AppBundle:Article')->getPrice($client,$article);
                if(!empty($cliprice)){
                    $unityPrice = $cliprice->getMontant(); 
                    // dump($unityPrice);                   
                }
            }
        }
        return $unityPrice;
    }
    
}
