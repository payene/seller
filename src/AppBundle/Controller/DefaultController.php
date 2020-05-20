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

    /**
     * @Route("/shop/article/select", name="select_article")
    **/
    public function selectArticleAction(Request $request, SessionInterface $session)
    {
        $typeId = $request->request->get("type-id");
        $em = $this->getDoctrine()->getManager(); 
        $typeArticle = $em->getRepository("AppBundle:TypeArticle")->find($typeId);
        $caracteristiques = $typeArticle->getCaracteristiques();
        $q = "select a " . 
                 "from AppBundle:Article a " .
                 "join a.typeArticle t " .
                 "where t.id = :typeId";
        foreach(  $caracteristiques as $caracteristique ){
            $q .= " and a.id in ( " . 
                                "select art".$caracteristique->getId().".id " .
                                "from AppBundle:Valeur v".$caracteristique->getId()." " .
                                "join v".$caracteristique->getId().".caracteristique c".$caracteristique->getId()." " .
                                "join v".$caracteristique->getId().".article art".$caracteristique->getId()." " .
                                "join art".$caracteristique->getId().".typeArticle t".$caracteristique->getId()." " .
                                "where c".$caracteristique->getId().".id = :cid" . $caracteristique->getId() . " " .
                                "and v".$caracteristique->getId().".valeurCaracteristique = :valeur" . $caracteristique->getId() . " " .
                                "and t".$caracteristique->getId().".id = :typeId " .
                            " )";
        }
        $query = $em->createQuery($q)
                    ->setParameter("typeId", $typeId);

        foreach(  $caracteristiques as $caracteristique ){
            $valeur = $request->request->get($caracteristique->getId());
            $query->setParameter("cid" . $caracteristique->getId(), $caracteristique->getId());
            $query->setParameter("valeur" . $caracteristique->getId(), $valeur);
        }
        $cart = $session->get("cart");
        $shop = [];
        $articles = $query->getScalarResult(); //Sous forme de liste
        $loggedUser = $this->getUser();
        $personnaliser = false;
        foreach ($articles as $key => $article) {
            if(!empty($loggedUser)){
                $suscriber = $loggedUser->getSuscriber();
                // dump($suscriber);
                if(!empty($suscriber)){
                    $client = $suscriber->getClient();
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
                        ->setParameter('articleId', $article["a_id"])
                        ->orderBy('p.id', 'DESC')
                        ->setMaxResults(1)
                        ->getQuery()
                        ->getOneOrNullResult()
                    ;
                    
                    // dump($price);
                    // exit;
                    //$medias = $em->getRepository('AppBundle:Media')->findBy(['article' => $article->getId()]);
                    $qtePanier = isset($cart[$article["a_id"]])?$cart[$article["a_id"]]["nbr"]:0;
                    $shop[] = array('article' => $article, 'client' => $client, 'price' => $price==null?null:$price->getMontant(), "qtePanier" => $qtePanier );
                    $personnaliser = true;                
                }
            }

            if($personnaliser == false){
                $qtePanier = isset($cart[$article["a_id"]])?$cart[$article["a_id"]]["nbr"]:0;
                $shop[] = array('article' => $article, "qtePanier" => $qtePanier);
            }
        }

        return new JsonResponse($shop);
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
                ->getRepository('AppBundle:TypeArticle')
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
        /* les variables articles ici références les types d'article */
        $articles = $em->getRepository('AppBundle:TypeArticle')
                ->getListByCat($page, $maxArticles, $cat);
        $loggedUser = $this->getUser();
        // exit;
        $shop = [];
        $nbrCart = $session->get('nbrCart');
        /* if(!empty($loggedUser)){
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
        } */
        
        $shop = [];
        foreach ($articles as $key => $typeArticle) {
            $option = [];
            $caracteristiques = $typeArticle->getCaracteristiques();
            foreach( $caracteristiques as $caracteristique ){
                $valeurs = $em->createQueryBuilder()
                            ->select("v")
                            ->from("AppBundle:Valeur", "v")
                            ->join("v.caracteristique", "c")
                            ->join("v.article", "a")
                            ->join("a.typeArticle", "t")
                            ->where("t.id = :id")
                            ->andWhere("c.id = :c_id")
                            ->setParameter("id", $typeArticle->getId())
                            ->setParameter("c_id", $caracteristique->getId())
                            ->getQuery()
                            ->getResult();
                $valeurPossibles = [];
                foreach($valeurs as $valeur){
                    if( !in_array($valeur->getValeurCaracteristique(), $valeurPossibles) ){
                        $valeurPossibles[] = $valeur->getValeurCaracteristique();
                    }
                }
                $option[] = [ "caracteristique" => $caracteristique, "valeurs" => $valeurPossibles ];
            }
            $shop[] = array('item' => $typeArticle, "options" => $option );
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

        $nom = $request->get("article");
//dump($nom);
        $mots = explode(" ", $nom);
        $cart = $session->get('cart');

        $qb = $em->createQueryBuilder()
                ->select('a')
                ->from('AppBundle:TypeArticle', 'a')
                ->join('a.category', 'c')
                ;
                $i = 0;
        foreach($mots as $mot){
            if($i == 0){ //Première importance
                $qb->where('a.designation like :mot'.$i)
                   ->orWhere('a.description like :mot'.$i)
                   ->orWhere('c.catname like :mot'.$i)
                   ->orWhere('c.catdesc like :mot'.$i)
                ->setParameter('mot'.$i, "%".$mot."%");
            }
            else{
                $qb->orWhere('a.designation like :mot'.$i)
                    ->orWhere('c.catname like :mot'.$i)
                    ->orWhere('c.catdesc like :mot'.$i)
                    ->setParameter('mot'.$i, "%".$mot.'%');
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
                $cats[] =$categorie;
                $ids[] = $categorie->getId();
            }
        }

        $loggedUser = $this->getUser();
        // exit;
        $shop = [];
        $nbrCart = $session->get('nbrCart');
        /* if(!empty($loggedUser)){
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
                    "nom" => $nom,
                    'cats' => $cats,
                ]);
            }
        } */
        
        $shop = [];
        foreach ($articles as $key => $typeArticle) {
            $option = [];
            $caracteristiques = $typeArticle->getCaracteristiques();
            foreach( $caracteristiques as $caracteristique ){
                $valeurs = $em->createQueryBuilder()
                            ->select("v")
                            ->from("AppBundle:Valeur", "v")
                            ->join("v.caracteristique", "c")
                            ->join("v.article", "a")
                            ->join("a.typeArticle", "t")
                            ->where("t.id = :id")
                            ->andWhere("c.id = :c_id")
                            ->setParameter("id", $typeArticle->getId())
                            ->setParameter("c_id", $caracteristique->getId())
                            ->getQuery()
                            ->getResult();
                $valeurPossibles = [];
                foreach($valeurs as $valeur){
                    if( !in_array($valeur->getValeurCaracteristique(), $valeurPossibles) ){
                        $valeurPossibles[] = $valeur->getValeurCaracteristique();
                    }
                }
                $option[] = [ "caracteristique" => $caracteristique, "valeurs" => $valeurPossibles ];
            }
            $shop[] = array('item' => $typeArticle, "options" => $option );
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
        $type = $request->query->get("type");
        $em = $this->getDoctrine()->getManager();   
        $user = $this->getUser();
        $suscriber = $user->getSuscriber();
        $client = $suscriber->getClient();
        $orders = $em->getRepository('AppBundle:Proformat');
        switch($type){
            case "non_p":
                $orders = $orders->findBy(['client' => $client, "payer" => false]);
                break;
            case "p":
                $orders = $orders->findBy(['client' => $client, "payer" => true]);
                break;
            case "non_l":
                $orders = $orders->findBy(['client' => $client, "livrer" => false]);
                break;
            case "l":
                $orders = $orders->findBy(['client' => $client,"livrer" => true]);
                break;
            default:
                $orders = $orders->findBy(['client' => $client]);
        }

        // dump($cart);
        //dump($orders);
        // replace this example code with whatever you need
        return $this->render('default/account.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * Displays a form to change password of an existing User entity.
     * @Route("/account/change/password/", name="account_password")
     * @Method({"GET", "POST"})
    */
    public function changePasswordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

            if (!$user) {
                throw $this->createNotFoundException('Compte non identifié.');
            }

            $formFactory = $this->container->get('fos_user.change_password.form.factory');

            $form = $formFactory->createForm();
            $form->setData($user);

            $form->handleRequest($request);
            if ($form->isValid()) {
                $userManager = $this->container->get('fos_user.user_manager');
                $userManager->updateUser($user);

                $this->addFlash("success", "Mot de passe modifié avec succès");
                return $this->redirectToRoute('account');
            }

            return $this->render("default/change_password.html.twig", array(
                            'user'      => $user,
                            'form'   => $form->createView(),
                ));
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
     * @Route("/shop/add/to/cart", name="add_to_cart")
     * * @Method({"POST","GET"})
    **/
    public function addToCartAction(Request $request)
    {
        //dump($request); exit();
        if (!$request->isXmlHttpRequest()) {
            // return new JsonResponse(array('message' => 'You can\'t access this by this way!'), 400);
        }
        
        $em = $this->getDoctrine()->getManager();           
        $article = $em->getRepository("AppBundle:Article")->find( $request->request->get("article-id") );
        $type=$article->getTypeArticle();
        $qte = $request->request->get("qte", 1);
        $session = $this->container->get('session'); 
        $nbrCart = $session->get('nbrCart');
        $cartAmount = $session->get('cartAmount', 0);
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
            $currNbr = $qte;
        }
        else{
            $currNbr = $order['nbr'] + $qte;
        }
        
        if( !isset($cart[$article->getId()]) ){
            $nbrCart++;
        }
        
        $rest = $article->getStock() - $currNbr;
        
        if($rest < 0) exit();

        $unityPrice = $this->getUniPrice($article, $loggedUser);
        $order = array('article' => $article, 'nbr' => $currNbr, 'price' =>$unityPrice);
        $cart[$article->getId()] = $order;
        

        $orderAmount = $unityPrice * $currNbr;

        /*$cartAmount = intval($cartAmount);
        if($cartAmount >= 0){
            $cartAmount+= ($unityPrice*$qte);
        }*/
        
        $cartAmount = ($cartAmount<0)?0: $cartAmount ;
        //dump($cartAmount);
        $cartAmount= $cartAmount + ($unityPrice*$qte);

        $session->set('cartAmount', $cartAmount);
        $orderAmount = number_format($orderAmount, 0, ',', ' ');
        $session->set('nbrCart', $nbrCart);
        $session->set('cart', $cart);
        // dump($cart);
        // exit;
        return new JsonResponse(array('nbrCart' => $nbrCart,  'article' => $article->getId(), 'nbrOrder' => $currNbr, 'orderAmount' => $orderAmount , 'cartAmount' => $cartAmount, 'type_id' => $type->getId() )); exit;
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
                //$currNbr = 1;
                exit();
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
     * @Route("/account/detail/{proforma}", name="detail_proforma")
    **/
    public function detailProformaAction(Request $request, Proformat $proforma)
    {
        return $this->render('default/detail_commande.html.twig', [
            'proforma' => $proforma
        ]);
    }

    /**
     * @Route("/proceed/proforma", name="proceed_proforma")
    **/
    public function proceedProformaAction(Request $request, \Swift_Mailer $mailer)
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

            $livraisonDomicile = $form["livraisonDomicile"]->getData();
            $dureeLivraison = $form["dureeLivraison"]->getData();
            $proforma = new Proformat();
            $proforma->setLivraisonDomicile($livraisonDomicile);
            $proforma->setDureeLivraison($dureeLivraison);
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
            $lpCollection = $em->getRepository("AppBundle:LigneProformat")->findByProformat($proforma);
            //Envoi de mail
            $message = (new \Swift_Message('Votre commande a été enregistrée avec succes !'))
            ->setFrom(['no-reply@lespetitsbras-com.mon.world' => "Les petits bras"])
            ->setTo($loggedUser->getEmail())
            ->setBody(
                $this->renderView(
                    'email/confirm.html.twig',
                    [
                        'proforma' => $proforma,
                        'lpCollection' => $lpCollection,
                    ]
                ),
                'text/html'
            );
            $mailer->send($message);
            $this->addFlash("success", 'Votre commande a été enregistrée avec succès !');

            $session->set('cart', null);
            $session->set('nbrCart', null);

            return $this->redirectToRoute('detail_proforma', array('proforma' => $proforma->getId()));
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
