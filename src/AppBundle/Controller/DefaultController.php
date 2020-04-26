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
use AppBundle\Entity\DeliveryAdress;
use UserBundle\Entity\User;
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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
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
     * @Route("/shop/search/", name="shop_article_search")
     **/
    public function searchArticleAction(Request $request, $page = 1, SessionInterface $session)
    {
        
        $em = $this->getDoctrine()->getManager();            
        $maxArticles = 100;

        $nom = $request->get("nom");

        $mots = explode(" ", $nom);
        $cart = $session->get('cart');

        $qb = $em->createQueryBuilder()
                ->select('a')
                ->from('AppBundle:Article', 'a');
                $i = 0;
        foreach($mots as $mot){
            if($i == 0){ //Première importance
                $qb->where('a.designation like :mot or a.description like :mot')
                ->setParameter('mot', "%".$mot."%");
            }
            else{
                $qb->orWhere('a.designation like :mot or a.description like :mot')
                    ->setParameter('mot', "%".$mot);
            }
            $i++;
        }

        $articles = $qb->getQuery()->getResult();
        
        //Recherche des catégories
        $cats = [];
        $ids = [];
        foreach($articles as $article){
            $categorie = $article->getCategory();
            if( !in_array($categorie->getId(), $ids) ){
                $cats[$categorie->getId()] = [ "categorie" => $categorie, "nb" => 1];
                $ids[] = $categorie->getId();
            }
            else{
                $cats[$categorie->getId()]["nb"] = $cats[$categorie->getId()]["nb"] + 1;
            }
        }

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
                return $this->render('default/shop-by-article.html.twig', [
                    'cart' => $cart,
                    'nbrCart' => $nbrCart,
                    'articles' => $shop,
                    //'categoryArray' => $categoryArray,
                    'cats' => $cats,
                ]);
            }
        }
        
        $shop = [];
        foreach ($articles as $key => $article) {
            $shop[] = array('item' => $article );
        }
        // replace this example code with whatever you need
        return $this->render('default/shop-by-article.html.twig', [
            'cart' => $cart,
            'nbrCart' => $nbrCart,
            'articles' => $shop,
            'cats' => $cats,
            "nom" => $nom,
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
     * @Route("/proforma/checkout", name="proforma_checkout")
    **/
    public function checkoutAction(Request $request, SessionInterface $session)
    {
        $user = $this->getUSer();
        $cart = $session->get('cart');
        $nbrCart = $session->get('nbrCart');
        $cartAmount = $session->get('cartAmount');
        if( $user == null ){
            //Formulaire de création d'utilisateur
            $user = new User();
            $form = $this->createForm('UserBundle\Form\UserType', $user);
            //l'utilisateur se connecte ou s'enregistre avant de continuer
            return $this->render('default/login_checkout.html.twig', [
                'cart' => $cart,
                'nbrCart' => $nbrCart,
                'cartAmount' => $cartAmount,
                "form" => $form->createView(),
            ]);
        }
        
        
        $deliveryAdress = new DeliveryAdress();
        $deliveryAdress->setEmail( $user->getEmail() );
        $em = $this->getDoctrine()->getManager();
        $suscriber = $user->getSuscriber();
        $lastDeliveryAdress = $em->getRepository("AppBundle:DeliveryAdress")->findOneBy(["suscriber" => $suscriber], ["id" => "desc"]);
        if( $lastDeliveryAdress != null ){//Dernière adresse sauvegardée
            $deliveryAdress->copy($lastDeliveryAdress); //Initialisation des valeur des champs
        }

        $form = $this->createForm('AppBundle\Form\DeliveryAdressType', $deliveryAdress);

        return $this->render('default/checkout.html.twig', [
            'cart' => $cart,
            'nbrCart' => $nbrCart,
            'cartAmount' => $cartAmount,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/proforma/checkout/login", name="proforma_checkout_login")
    **/
    public function checkoutLoginAction(Request $request)
    {
        $_username = $request->request->get("_username");
        $_password = $request->request->get("_password");
        
        $factory = $this->get('security.encoder_factory');
        // recupération de l'utilisateur par son nul ou email
        $user_manager = $this->get('fos_user.user_manager');
        $user = $user_manager->findUserByUsernameOrEmail($_username);
        if ($user == null){
            $this->addFlash("user_checkout_error", "Ce nom d'utilisateur n'existe pas");
            return $this->redirectToRoute("proforma_checkout");
        }
        else{
            //Vérification du mot de passe
            $encoder = $factory->getEncoder($user);
            $salt = $user->getSalt();
            if(!$encoder->isPasswordValid($user->getPassword(), $_password, $salt)) {
                $this->addFlash("password_checkout_error", "Nom d'utilisateur ou mot de passe incorrect");
                return $this->redirectToRoute("proforma_checkout");
            }
        }
        //Connexion de l'utilisateur
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->set('_security_main', serialize($token));
        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
        //Utilisateur connecté! Etape suivante
        return $this->redirectToRoute("proforma_checkout");
        
    }

    /**
     * @Route("/proforma/checkout/register", name="proforma_checkout_register")
    **/
    public function checkoutRegisterAction(Request $request)
    {
        $user = new User();
        $user->setEnabled(True);
        $form = $this->createForm('UserBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $userRepo = $em->getRepository("UserBundle:User");
            $autre = $userRepo->findByUsername($user->getUsername());
            $autre2 = $userRepo->findByEmail( $user->getEmail() );
            //dump( $autre ); exit();
            if( $autre  != null ){
                $this->addFlash("register_username_error", "Ce nom d'utiisateur existe déjà");
            }
            else if( $autre2 != null ){
                $this->addFlash("register_email_error", "Cet email existe déjà");
            }
            else{
                $suscriber = $user->getSuscriber();
                $client = $suscriber->getClient();
                $client->setEmail($user);

                $autreClient = $em->getRepository("AppBundle:Client")->findByTelephone($client->getTelephone());
                if($autreClient != null){
                    $this->addFlash("telephone_error", "Ce numéro de téléphone est déjoà utilisé");
                    return $this->redirectToRoute("proforma_checkout");
                }

                $em->persist($user);
                $em->persist($suscriber);
                $em->persist($client);
                $em->flush();
                //login de l'utilisatuer
                $token = new UsernamePasswordToken($user, $user->getPassword(), "public", $user->getRoles());
                $this->get("security.token_storage")->setToken($token);
                $event = new InteractiveLoginEvent($request, $token);
                $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
            }
        }
        else {
            //Mots de passe incorrect
            $this->addFlash("register_password_error", "Les mots de passe doivent être identiques");
        }
        

        //Utilisateur connecté! Etape suivante
        return $this->redirectToRoute("proforma_checkout");
        
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
        
        $deliveryAdress = new DeliveryAdress();
        $form = $this->createForm('AppBundle\Form\DeliveryAdressType', $deliveryAdress);
        

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager(); 
            $suscriber = $loggedUser->getSuscriber();
            
            if( $request->request->get("save") != false ){//le bouton "sauvegarder les informations" est coché
                $deliveryAdress->setSuscriber($suscriber);
            }

            $em->persist($deliveryAdress);
            $em->flush();

            $proforma = new Proformat();
            $proforma->setDateproformat(new \DateTime());
            $proforma->setDeliveryAdress($deliveryAdress);
            if(!empty($suscriber)){
                $client = $suscriber->getClient();
                $proforma->setClient($client);
            }
            else{
                throw new \Exception("Error Processing Request", 1);
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
        }
        else{
            $this->addFlash("error", "Formulaire invalide");
            return $this->redirectToRoute('proforma_checkout', array('proforma' => $proforma->getId()));
        }
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
