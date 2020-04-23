<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Article;
use Symfony\Component\Form\FormError;
use AppBundle\Entity\LigneVente;
use AppBundle\Entity\Client;
use AppBundle\Entity\Vente;
use AppBundle\Entity\MvtStock;
use AppBundle\Entity\ArticleStock;
use AppBundle\Entity\Paiement;
use AppBundle\Repository\VenteRepository;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;



/**
 *
 * @Route("backoffice/vente")
 */
class VenteController extends Controller
{



    /**
     * retire un article au panier
     * 
     * @Route(name="remove_from_card",path="/card/remove/{id}")
     * @Method({"GET"})
     */
    public function removeFromCard(Request $request, $id)
    {
        $card = $this->getCard();
        $new_card = [];


        foreach ($card as $article) {

            if($article['id_art']!=$id)
            {
                array_push($new_card, [
                    'id_art' => $article['id_art'],
                    'designation' => $article['designation'],
                    'qte' => $article['qte'],
                    'pu' => $article['pu']
                ]);
            }
        }
        
        $session = new Session();
        $session->set('card', $new_card);

        if($request->isXmlHttpRequest() )
        {
            return new Response(json_encode([
                'statut' => "succes",
                "msg" => "Article retiré du panier.",
                "artInCardNumber" => count($this->getCard() ),
            ]));
        }
        return $this->redirectToRoute('nouvelle_vente');
    }


    /**
     * 
     * 
     * @Route(name="destroy_card",path="/card/destroy")
     * @Method({"GET"})
     */
    public function destroyCard(Request $request)
    {
        $this->eraseCard();
        if($request->isXmlHttpRequest() )
        {
                return new Response(json_encode([
                    'statut' => "succes",
                    "msg" => "Panier vidé.",

                ]));
                
        }
        return $this->redirectToRoute('nouvelle_vente');
    }






    /**
     * @Route(name="nouvelle_vente",path="/vente/new")
     * @Method({"GET", "POST"})
     */
    public function nouvelleVenteAction(Request $request)
    {
        
        $session = $this->get('session');
        $editModeOn = $session->get('edit_mode_on');
        if($editModeOn){
            $this->eraseCard(); 
            $session->set('edit_mode_on', false);           
        }



        $session = new Session();
        $em = $this->getDoctrine()->getManager();
          

        $card = $this->getCard();
        $sessionClient = $session->get('client');
        $clientSelected = null;
        if(!empty($sessionClient)){
            $clientSelected = $em->getRepository('AppBundle:Client')->findOneById($sessionClient->getId());
        }
        $ligneVente = new LigneVente();
        $ligne_vente_form = $this->createFormBuilder()
            ->add('article',EntityType::class,[
                'label' => "Article",
                 'required' => false,
                 'class' => Article::class,
                  'query_builder' => function ($er) {
                    return $er->createQueryBuilder('a')
                        ->where('a.stock > 0')
                        ->orderBy('a.designation', 'ASC')
                        ;
                },
                'attr' => []
             ])
             ->add('client',EntityType::class,[
                 'label' => "Client",
                 'required' => false,
                 'class' => Client::class,
                 'mapped' => false,
                 'data' => $clientSelected,
                'attr' => []
             ]) 
            ->add('qte',IntegerType::class,[ 
                'required' => false,
                'data' => 1,
                'attr' => [
                    'min' => 1,
                ]
            ])
            ->getForm();  

        $card = $this->getCard();


                                    
        $ligne_vente_form->handleRequest($request);

        $article = $ligne_vente_form['article']->getData();
        $client = $ligne_vente_form['client']->getData();
        $qteLigneVente = $ligne_vente_form['qte']->getData();

        if ( $ligne_vente_form->isSubmitted() && $ligne_vente_form->isValid()){  

            // $unitPrice = $em->getRepository('AppBundle:Prix')->findOneById
            // entité Prix
            $query = $em->createQueryBuilder();
            if(!empty($client)){
                $queryPrice = $query
                    ->select('p')
                    ->from('AppBundle:Prix', 'p')
                    ->join('p.article', 'a')
                    ->join('p.client', 'c')
                    ->andwhere(":today BETWEEN p.dateDebut AND p.dateFin ")
                    ->andwhere("a.id = :articleId ")
                    ->andwhere("c.id = :clientId ")
                    ->setParameter('articleId',  $article->getId() )
                    ->setParameter('clientId',  $client->getId() )
                    ->setParameter('today', (new \DateTime())->format('Y-m-d') . " 00:00:00" )
                    ->orderBy('p.id' , 'DESC')
                    ->setMaxResults(1)
                    ->getQuery()->getResult();
            }
            
            $priceEntity = isset($queryPrice[0]) ? $queryPrice[0] : null;
            if(!empty($priceEntity)){
                $unitPrice = $priceEntity->getMontant();
            }
            elseif($article->getDefaultPrice() > 0){
                $unitPrice = $article->getDefaultPrice();
            }
            else{
                $query = $em->createQueryBuilder();
                
                $latestStockQuery = $em->createQueryBuilder()
                   ->select('s')
                   ->from('AppBundle:Stock','s')
                   ->join('s.arrivage','a')
                   ->where('a.article = :article')
                   ->andWhere('s.qte > 0')
                   ->setParameter('article', $article->getId())
                   ->orderBy('s.id', 'DESC')
                   ->setMaxResults(1)
                   ->getQuery() 
                   ->getResult();
                $latestStock = isset( $latestStockQuery[0]) ? $latestStockQuery[0] : null;
                if(!empty($latestStock)){
                    $unitPrice = $latestStock->getPunit();                    
                }
                else{
                    return new Response(json_encode([
                        'statut' => "echec",
                        "msg" => "Stock non disponible",
                        "artInCardNumber" => count($this->getCard() )
                    ]));
                }
            }

            $card = $this->addArticleToCard($article,$qteLigneVente, $unitPrice, $client);
            if($request->isXmlHttpRequest() )
            {

                return new Response(json_encode([
                    'statut' => "succes",
                    "msg" => "Article ajoute dans le panier",
                    "artInCardNumber" => count($this->getCard() )
                ]));
                

            }
        }
        // dump($card);

        $vente_form = $this->createFormBuilder()
            ->add('total_ht',HiddenType::class,[
                   'label' => "Total HT",
                 'required' => true,
                 'data' => $this->getPrixTotalPanier() ,
                'attr' => []
             ]) 
             ->add('tva',IntegerType::class,[
                 'label' => "TVA",
                 'required' => true,
                  'data' => 0,
                'attr' => []
             ]) 
            ->add('remise',IntegerType::class,[ 
                'required' => true,
                 'data' => 0,
                'attr' => ['min' => 0,]
            ])

            ->add('nom', TextType::class, array('required'=>false, 'label'=>'Nom','attr'=>array('class'=>'form-control','placeholder'=>'Nom du client')))
            ->add('prenom', TextType::class, array('required'=>false, 'label'=>'Prenom','attr'=>array('class'=>'form-control','placeholder'=>'Prenom du client')))
            ->add('raisoc', TextareaType::class, array('required'=>false, 'label'=>'Raisoc','attr'=>array('class'=>'form-control','placeholder'=>'Raison ')))
            ->add('adresse', TextareaType::class, array('required'=>false, 'label'=>'Adresse','attr'=>array('class'=>'form-control','placeholder'=>'Adresse client')))
            ->add('telephone', TextType::class, array('required'=>false, 'label'=>'Telephone','attr'=>array('class'=>'form-control','placeholder'=>'Telephone client')))
            ->add('email', TextType::class, array('required'=>false, 'label'=>'E-mail','attr'=>array('class'=>'form-control','placeholder'=>'E-mail client')))

            ->getForm();  
        //dump($ligne_vente_form['client']->getData()); exit;
        $vente_form->handleRequest($request);
        // $qteVendu = $data['qte']
        if ( $vente_form->isSubmitted())  {

            

            if ( $vente_form->isValid())  {

                //vérifier si article dans le panier est en qte suffisante en stock
                $articleRepo = $em->getRepository("AppBundle:Article");
                foreach ($card as $lignePanier) {

                    $article = $articleRepo->find($lignePanier['id_art'] ) ;

                    if($article->getStock() < $lignePanier['qte']  )
                    {
                        return new Response(json_encode([
                            "statut" => 'erreur',
                            "msg" => "La quantité non disponible pour l'article ".$lignePanier['designation']." "
                        ]));
                        break;
                    }   

                }

                $client = null;
                if( $session->get('client')  ){
                    $client = $this->getDoctrine()->getRepository('AppBundle:Client')->findOneById($session->get('client'));
                }
                elseif(!empty($ligne_vente_form['client']->getData())){
                    $client = $this->getDoctrine()->getRepository('AppBundle:Client')->findOneById($ligne_vente_form['client']->getData());
                }
                else 
                {
                    //vérifier l'unicité du telephone
                    $clt = $em->getRepository("AppBundle:Client")
                              ->findOneByTelephone($vente_form['telephone']->getData());

                    if($clt)
                    {
                        return new Response(json_encode([
                            "statut" => 'erreur',
                            "msg" => "Le téléphone du client existe déjà, veuillez le modifier"
                        ]));
                    }           

                    //créer un nouveau client
                    $client = new Client();
                    $client->setNom($vente_form['nom']->getData());
                    $client->setPrenom($vente_form['prenom']->getData());
                    $client->setRaisoc($vente_form['raisoc']->getData());
                    $client->setAdresse($vente_form['adresse']->getData());
                    $client->setTelephone($vente_form['telephone']->getData());
                    $client->setEmail($vente_form['email']->getData());

                    $em->persist($client);
                }

                // dump($card);    

                $vente = new Vente();
                $vente->setCreatedBy($this->getUser());
                $vente->setClient($client);
                $vente->setTva( $vente_form['tva']->getData() );
                $vente->setRemise( $vente_form['remise']->getData() );
                $vente->setTotalHt( $this->getPrixTotalPanier() );
                $vente->setResteAPayer( $this->getPrixTotalPanier() +  $vente_form['tva']->getData() - $vente_form['remise']->getData());
                $vente->setCredit( 0 );
                $vente->setCreatedAt(new \DateTime());

                $em->persist($vente);


                $lignes_vente_to_persist = [];

                foreach ($card as $elementPanier) {
                    $qteTotalAVendre = $elementPanier['qte'];
                    $qteRepport = $qteTotalAVendre;
                    $articleStock =  $em->getRepository('AppBundle:Article')->findOneById($elementPanier['id_art']);  
                    //verification du stock a utiliser pour la ligne vente et le mvt stock
                    $stockAvailableArray = $em->createQueryBuilder()
                       ->select('s')
                       ->from('AppBundle:Stock','s')
                       ->join('s.arrivage','a')
                       ->where('a.article = :article')
                       ->andWhere('s.qte > 0')
                       ->setParameter('article', $articleStock->getId())
                       ->orderBy('s.id', 'ASC')
                       ->getQuery() 
                       ->getResult();

                    
                    $ligneVente = new LigneVente();
                    foreach ($stockAvailableArray as $key => $stockAv) {
                        # code...
                        $arvg = $stockAv->getArrivage();

                        $qteRepport = $qteTotalAVendre - $stockAv->getQte();
                        $ligneVente->setMontant($elementPanier['pu']);
                        $ligneVente->setCreatedAt(new \DateTime());
                        if($qteRepport <= 0){
                            $qteTrouvee = $qteTotalAVendre;
                        }
                        else{
                            $qteTrouvee = $qteTotalAVendre - $qteRepport;
                            $qteTotalAVendre -= $qteTrouvee; 
                        }

                        $ligneVente->setQte($ligneVente->getQte() + $qteTrouvee);
                        $newValStockAvailable =  $stockAv->getQte() - $qteTrouvee;                    
                        $stockAv->setQte($newValStockAvailable);
                        
                        $ligneVente->setQte($qteTrouvee);
                        $newValStockAvailable =  $stockAv->getQte() - $qteTrouvee;                    
                        $stockAv->setQte($newValStockAvailable);
                        $arrivage = $stockAv->getArrivage();


                        $ligneVente->setArticle($articleStock);
                        $ligneVente->setVente($vente);
                        $em->persist($ligneVente);
                        $em->merge($stockAv);
                        array_push($lignes_vente_to_persist, $ligneVente);

                        $newStock =  $articleStock->getStock() -  $qteTrouvee;
                        $articleStock->setStock( $newStock);

                        $em->merge($articleStock);
                        $mvt = new MvtStock();
                        $mvt->setQteMvt($ligneVente->getQte());
                        $mvt->setStock($newStock);
                        $mvt->setStockArvg($arrivage->getQte());
                        $mvt->setArrivage($arrivage);
                        $mvt->setLigneVente($ligneVente);
                        $mvt->setMvtType(3);
                        $mvt->setCreatedAt(new \DateTime());
                        $mvt->setCreatedBy($this->getUser());
                        $em->persist($mvt);
                    }
                }

                $em->flush();

                $this->eraseCard();

                return new Response(json_encode(["statut" => 'succes', "msg" => "Vente enregistrée.", "factureUri" => $this->generateUrl( 'facture_vente', ['id'=> $vente->getId() ] ) ])) ;

                // return $this->redirectToRoute('facture_vente', ['id'=> $vente->getId() ]);
            }
        }


        $inventaireCourant =  $this->getDoctrine()
            ->getManager()
            ->createQueryBuilder()
            ->select('i')
            ->from('AppBundle:Inventaire', 'i')
            ->where("i.dateFin IS NULL ")
            ->getQuery()
            ->getOneOrNullResult();

        return $this->render('Vente/nouvelle_vente.html.twig', array(
            'ligne_vente_view' => $ligne_vente_form->createView(),  
            'card' => $card,
            'prix_total_panier' => $this->getPrixTotalPanier(),
            "artInCardNumber" => count($this->getCard() ),

            'vente_view' => $vente_form->createView(),

            'inventaireCourant' => $inventaireCourant
        ));
    }


     /**
     * 
     * 
     * @Route(name="prepare_card_edit",path="/card/preparation/edition/{id}")
     * @Method({"GET"})
     */
    public function prepareCard(Request $request, Vente $vente)
    {
        
        $this->eraseCard();
        $session = $this->get('session');
        $session->set('edit_mode_on', true);
        $lignesV =  $vente->getLignesVente();
        foreach ($lignesV as $key => $ligne) {
            $article = $ligne->getArticle();
            $qte = $ligne->getQte();
            $pu = $ligne->getMontant();
            $client = $ligne->getVente()->getClient();
            $lv = $ligne->getId();
            // $ligne->setDel(1);
            $em = $this->getDoctrine()->getManager();
            $em->merge($ligne);
            $em->flush();
            $this->addArticleToCard($article,  $qte, $pu, $client, $lv);
        }
        return $this->redirectToRoute('edition_vente', array('id' => $vente->getId()));
    }


    /**
     * @Route(name="edition_vente",path="/vente/edition/{id}")
     * @Method({"GET", "POST"})
     */
    public function editionVenteAction(Request $request, Vente $vente)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $card = $this->getCard();
        $sessionClient = $vente->getClient();
        $clientSelected = null;
        if(!empty($sessionClient)){
            $clientSelected = $em->getRepository('AppBundle:Client')->findOneById($sessionClient->getId());
        }
        $ligneVente = new LigneVente();
        $ligne_vente_form = $this->createFormBuilder()
            ->add('article',EntityType::class,[
                'label' => "Article",
                 'required' => false,
                 'class' => Article::class,
                'attr' => []
             ])
             ->add('client',EntityType::class,[
                 'label' => "Client",
                 'required' => false,
                 'class' => Client::class,
                 'mapped' => false,
                 'data' => $clientSelected,
                'attr' => []
             ]) 
            ->add('qte',IntegerType::class,[ 
                'required' => false,
                'data' => 1,
                'attr' => [
                    'min' => 1,
                ]
            ])
            ->getForm();  

        $card = $this->getCard();


                                    
        $ligne_vente_form->handleRequest($request);

        $article = $ligne_vente_form['article']->getData();
        $client = $ligne_vente_form['client']->getData();
        $qteLigneVente = $ligne_vente_form['qte']->getData();

        if ( $ligne_vente_form->isSubmitted() && $ligne_vente_form->isValid()){  

            // $unitPrice = $em->getRepository('AppBundle:Prix')->findOneById
            // entité Prix
            $query = $em->createQueryBuilder();
            $queryPrice = $query
                ->select('p')
                ->from('AppBundle:Prix', 'p')
                ->join('p.article', 'a')
                ->join('p.client', 'c')
                ->andwhere(":today BETWEEN p.dateDebut AND p.dateFin ")
                ->andwhere("a.id = :articleId ")
                ->andwhere("c.id = :clientId ")
                ->setParameter('articleId',  $article->getId() )
                ->setParameter('clientId',  $client->getId() )
                ->setParameter('today', (new \DateTime())->format('Y-m-d') . " 00:00:00" )
                ->orderBy('p.id' , 'DESC')
                ->setMaxResults(1)
                ->getQuery()->getResult();
            
            $priceEntity = isset($queryPrice[0]) ? $queryPrice[0] : null;
            if(!empty($priceEntity)){
                $unitPrice = $priceEntity->getMontant();
            }
            elseif($article->getDefaultPrice() > 0){
                $unitPrice = $article->getDefaultPrice();
            }
            else{
                $query = $em->createQueryBuilder();
                
                $latestStockQuery = $em->createQueryBuilder()
                   ->select('s')
                   ->from('AppBundle:Stock','s')
                   ->join('s.arrivage','a')
                   ->where('a.article = :article')
                   ->andWhere('s.qte > 0')
                   ->setParameter('article', $article->getId())
                   ->orderBy('s.id', 'DESC')
                   ->setMaxResults(1)
                   ->getQuery() 
                   ->getResult();
                $latestStock = isset( $latestStockQuery[0]) ? $latestStockQuery[0] : null;
                if(!empty($latestStock)){
                    $unitPrice = $latestStock->getPunit();                    
                }
                else{
                    return new Response(json_encode([
                        'statut' => "echec",
                        "msg" => "Stock non disponible",
                        "artInCardNumber" => count($this->getCard() )
                    ]));
                }
            }

            $card = $this->addArticleToCard($article,$qteLigneVente, $unitPrice, $client);
            if($request->isXmlHttpRequest() )
            {
                return new Response(json_encode([
                    'statut' => "succes",
                    "msg" => "Article ajoute dans le panier",
                    "artInCardNumber" => count($this->getCard() )
                ]));
            }
        }

        $vente_form = $this->createFormBuilder()
            ->add('total_ht',HiddenType::class,[
                   'label' => "Total HT",
                 'required' => true,
                 'data' => $this->getPrixTotalPanier() ,
                'attr' => []
             ]) 
             ->add('tva',IntegerType::class,[
                 'label' => "TVA",
                 'required' => true,
                 'data' => $vente->getTva(),
                'attr' => []
             ]) 
            ->add('remise',IntegerType::class,[ 
                'required' => true,
                'data' => $vente->getRemise(),
                'attr' => ['min' => 0,]
            ])

            ->add('nom', TextType::class, array('required'=>false, 'label'=>'Nom','attr'=>array('class'=>'form-control','placeholder'=>'Nom du client')))
            ->add('prenom', TextType::class, array('required'=>false, 'label'=>'Prenom','attr'=>array('class'=>'form-control','placeholder'=>'Prenom du client')))
            ->add('raisoc', TextareaType::class, array('required'=>false, 'label'=>'Raisoc','attr'=>array('class'=>'form-control','placeholder'=>'Raison ')))
            ->add('adresse', TextareaType::class, array('required'=>false, 'label'=>'Adresse','attr'=>array('class'=>'form-control','placeholder'=>'Adresse client')))
            ->add('telephone', TextType::class, array('required'=>false, 'label'=>'Telephone','attr'=>array('class'=>'form-control','placeholder'=>'Telephone client')))
            ->add('email', TextType::class, array('required'=>false, 'label'=>'E-mail','attr'=>array('class'=>'form-control','placeholder'=>'E-mail client')))

            ->getForm();  
        //dump($ligne_vente_form['client']->getData()); exit;
        $vente_form->handleRequest($request);
        // $qteVendu = $data['qte']
        if ( $vente_form->isSubmitted() && $vente_form->isValid())  {
            //vérifier si article dans le panier est en qte suffisante en stock
            $articleRepo = $em->getRepository("AppBundle:Article");
            foreach ($card as $lignePanier) {
                $article = $articleRepo->find($lignePanier['id_art'] ) ;
                if($article->getStock() < $lignePanier['qte']  )
                {
                    return new Response(json_encode([
                        "statut" => 'erreur',
                        "msg" => "La quantité non disponible pour l'article ".$lignePanier['designation']." "
                    ]));
                    break;
                }
            }

            $client = null;
            if( $session->get('client')  ){
                $client = $this->getDoctrine()->getRepository('AppBundle:Client')->findOneById($session->get('client'));
            }
            elseif(!empty($ligne_vente_form['client']->getData())){
                $client = $this->getDoctrine()->getRepository('AppBundle:Client')->findOneById($ligne_vente_form['client']->getData());
            }
            else 
            {
                //vérifier l'unicité du telephone
                $clt = $em->getRepository("AppBundle:Client")
                          ->findOneByTelephone($vente_form['telephone']->getData());
                if($clt)
                {
                    return new Response(json_encode([
                        "statut" => 'erreur',
                        "msg" => "Le téléphone du client existe déjà, veuillez le modifier"
                    ]));
                }           

                //créer un nouveau client
                $client = new Client();
                $client->setNom($vente_form['nom']->getData());
                $client->setPrenom($vente_form['prenom']->getData());
                $client->setRaisoc($vente_form['raisoc']->getData());
                $client->setAdresse($vente_form['adresse']->getData());
                $client->setTelephone($vente_form['telephone']->getData());
                $client->setEmail($vente_form['email']->getData());

                $em->persist($client);
            }
            /* $vente = new Vente();
            $vente->setCreatedBy($this->getUser());
            $vente->setClient($client);
            $vente->setTva( $vente_form['tva']->getData() );
            $vente->setRemise( $vente_form['remise']->getData() );
            $vente->setTotalHt( $this->getPrixTotalPanier() );
            $vente->setResteAPayer( $this->getPrixTotalPanier() +  $vente_form['tva']->getData() - $vente_form['remise']->getData());
            $vente->setCredit( 0 );
            $vente->setCreatedAt(new \DateTime());*/
            $vente->setTva( $vente_form['tva']->getData() );
            $vente->setRemise( $vente_form['remise']->getData() );
            $vente->setTotalHt( $this->getPrixTotalPanier() );
            $vente->setResteAPayer( $this->getPrixTotalPanier() +  $vente_form['tva']->getData() - $vente_form['remise']->getData());

            $em->persist($vente);

            // dump($vente); exit;
            $lignes_vente_to_persist = [];

            // $ligneVenteArray = $em->createQueryBuilder()
            //    ->select('lv')
            //    ->from('AppBundle:LigneVente','lv')
            //    ->andWhere('lv.vente = :vente')
            //    ->setParameter('vente', $vente->getId())
            //    ->getQuery() 
            //    ->getResult();
            // if(!empty($ligneVenteArray)){
            //     foreach ($ligneVenteArray as $key => $ligneV) {
                    
            //     }
            // }

            // foreach ($variable as $key => $value) {
            //     # code...
            // }
            $newCard = [];
            foreach ($card as $elementPanier) {
                //pour chaque ligne vente à annuler, recuperer les mvt stock de type vente et creer les mvt stock contriares pour equilibrer les stocks ensuiter creer les mvt stock de type vente avec les nouvelles quantités
                
                $ligneVente = $em->getRepository('AppBundle:LigneVente')->findOneBy(['article' => $elementPanier['id_art'], 'vente' => $vente->getId()]);
                $qteTotalAVendre = $elementPanier['qte'];
                $prixUnitVente = $elementPanier['pu'];
                if(!empty($ligneVente)){
                    $ligneVenteEdited[] = $ligneVente->getId();
                    $artOfLv = $ligneVente->getArticle();
                    if($qteTotalAVendre != $ligneVente->getQte() || $prixUnitVente !=  $ligneVente->getMontant()){
                        // Si changement de valeur dans la ligne vente, recuperer les mvt stocks de type vente concernes par la ligne vente et repasseer en ecriture d'anulation en mvt stock de type retour et mettre a jour les arrivages et les stock article correpspondants 
                        $oldMvtStocks = $ligneVente->getMvtsCollection();
                
                        foreach ($oldMvtStocks as $key => $oldMvt) {
                            $oldArrivage = $oldMvt->getArrivage();
                            $oldStock = $oldArrivage->getStock();
                            $newStock = $artOfLv->getStock() + $oldMvt->getQteMvt();
                            $returnArticleMvt = new MvtStock();
                            $returnArticleMvt->setQteMvt($oldMvt->getQteMvt());
                            $returnArticleMvt->setStock($newStock);
                            $oldStock->setQte($newStock);
                            $returnArticleMvt->setStockArvg($oldArrivage->getQte());
                            $returnArticleMvt->setArrivage($oldArrivage);
                            $returnArticleMvt->setLigneVente($ligneVente);
                            $returnArticleMvt->setMvtType(4);
                            $returnArticleMvt->setCreatedAt(new \DateTime());
                            $returnArticleMvt->setCreatedBy($this->getUser());
                            $em->persist($returnArticleMvt);
                            $em->persist($oldStock);
                        }
                        $ligneVente->setDel(1);
                        $em->merge($ligneVente);
                        $em->flush();
                        $newCard[] = $elementPanier;
                    } 
                    else{
                        //$ligneVente->setDel(0);
                        $em->merge($ligneVente);
                    }             
                }
                $newCard[] = $elementPanier;


            }

            $deletedLigneVentes = $em->createQueryBuilder()
               ->select('lv')
               ->from('AppBundle:LigneVente','lv')
               ->where('lv.id NOT IN (:edited)')
               ->andWhere('lv.vente = :vente')
               ->andWhere('lv.del = 0')
               ->setParameter('vente', $vente->getId())
               ->orderBy('lv.id', 'ASC')
               ->setParameter('edited', $ligneVenteEdited)
               ->getQuery() 
               ->getResult();

            if(!empty($deletedLigneVentes)){
                foreach ($lvToDel as $key => $deletedLigneVentes) {
                    $oldMvtStocks = $em->createQueryBuilder()
                        ->select('m')
                        ->from('AppBundle:MvtStock','m')
                        ->where('m.ligneVente = :lv')
                        ->setParameter('lv', $lvToDel->getId())
                        ->orderBy('s.id', 'ASC')
                        ->getQuery() 
                        ->getResult();
                    foreach ($oldMvtStocks as $key => $oldMvt) {
                        $oldArrivage = $oldMvt->getArrivage();
                        $oldStock = $oldArrivage->getStock();
                        $newStock = $articleStock->getStock() + $oldMvt->getQte();
                        $returnArticleMvt = new MvtStock();
                        $returnArticleMvt->setQteMvt($oldMvt->getQte());
                        $returnArticleMvt->setStock($newStock);
                        $oldStock->setQte($newStock);
                        $returnArticleMvt->setStockArvg($oldArrivage->getQte());
                        $returnArticleMvt->setArrivage($oldArrivage);
                        $returnArticleMvt->setLigneVente($ligneVente);
                        $returnArticleMvt->setMvtType(4);
                        $returnArticleMvt->setCreatedAt(new \DateTime());
                        $returnArticleMvt->setCreatedBy($this->getUser());
                        $em->persist($returnArticleMvt);
                        $em->persist($oldStock);
                    }
                    $deletedLigneVentes->setDel(1);
                    $em->merge($deletedLigneVentes);
                    $em->flush();
                }
            }

            foreach ($newCard as $elementPanier) { 
                $qteTotalAVendre = $elementPanier['qte'];
                $qteRepport = $qteTotalAVendre;
                $articleStock =  $em->getRepository('AppBundle:Article')->findOneById($elementPanier['id_art']);  
                //verification du stock a utiliser pour la ligne vente et le mvt stock
                $stockAvailableArray = $em->createQueryBuilder()
                   ->select('s')
                   ->from('AppBundle:Stock','s')
                   ->join('s.arrivage','a')
                   ->where('a.article = :article')
                   ->andWhere('s.qte > 0')
                   ->setParameter('article', $articleStock->getId())
                   ->orderBy('s.id', 'ASC')
                   ->getQuery() 
                   ->getResult();
                $previousSoldStock =0;

                $ligneVente = new LigneVente();
                foreach ($stockAvailableArray as $key => $stockAv) {
                    # code...
                    $arvg = $stockAv->getArrivage();

                    $qteRepport = $qteTotalAVendre - $stockAv->getQte();
                    $ligneVente->setMontant($elementPanier['pu']);
                    $ligneVente->setCreatedAt(new \DateTime());
                    if($qteRepport <= 0){
                        $qteTrouvee = $qteTotalAVendre;
                    }
                    else{
                        $qteTrouvee = $qteTotalAVendre - $qteRepport;
                        $qteTotalAVendre -= $qteTrouvee; 
                    }

                    $ligneVente->setQte($ligneVente->getQte() + $qteTrouvee);
                    $newValStockAvailable =  $stockAv->getQte() - $qteTrouvee;                    
                    $stockAv->setQte($newValStockAvailable);
                    $newValStockAvailable =  $stockAv->getQte() - $qteTrouvee;                    
                    $stockAv->setQte($newValStockAvailable);
                    $arrivage = $stockAv->getArrivage();


                    $ligneVente->setArticle($articleStock);
                    $ligneVente->setVente($vente);
                    $em->persist($ligneVente);
                    $em->merge($stockAv);
                    array_push($lignes_vente_to_persist, $ligneVente);

                    $newStock =  $articleStock->getStock() -  $qteTrouvee;
                    $articleStock->setStock( $newStock);

                    $em->merge($articleStock);
                    $mvt = new MvtStock();
                    $mvt->setQteMvt($qteTrouvee);
                    $mvt->setStock($newStock);
                    $mvt->setStockArvg($arrivage->getQte());
                    $mvt->setArrivage($arrivage);
                    $mvt->setLigneVente($ligneVente);
                    $mvt->setMvtType(3);
                    $mvt->setCreatedAt(new \DateTime());
                    $mvt->setCreatedBy($this->getUser());
                    $em->persist($mvt);
                }
            }

            $em->flush();

            $this->eraseCard();

            return new Response(json_encode(["statut" => 'succes', "msg" => "Vente enregistrée.", "factureUri" => $this->generateUrl( 'facture_vente', ['id'=> $vente->getId() ] ) ])) ;

            // return $this->redirectToRoute('facture_vente', ['id'=> $vente->getId() ]);
        }     


        $inventaireCourant =  $this->getDoctrine()
            ->getManager()
            ->createQueryBuilder()
            ->select('i')
            ->from('AppBundle:Inventaire', 'i')
            ->where("i.dateFin IS NULL ")
            ->getQuery()
            ->getOneOrNullResult();

        return $this->render('Vente/edition_vente.html.twig', array(
            'ligne_vente_view' => $ligne_vente_form->createView(),  
            'card' => $card,
            'vente' => $vente,
            'prix_total_panier' => $this->getPrixTotalPanier(),
            "artInCardNumber" => count($this->getCard() ),

            'vente_view' => $vente_form->createView(),

            'inventaireCourant' => $inventaireCourant
        ));
    }


  
    /**
     * @Route(name="facture_vente",path="/facture/voir/{id}")
     * @Method({"GET"})
     */
    public function facture(Request $request, Vente $vente)
    {
        $em = $this->getDoctrine()->getManager();
        $ligneVenteArray = $em->createQueryBuilder()
           ->select('lv')
           ->from('AppBundle:LigneVente','lv')
           ->andWhere('lv.vente = :vente')
           ->andWhere('lv.del = 0')
           ->setParameter('vente', $vente->getId())
           ->orderBy('lv.createdAt', 'DESC')
           ->getQuery() 
           ->getResult();
           // dump($deletedLigneVentes);
        // exit;
        return $this->render('Vente/facture.html.twig',[
            'vente' => $vente,
            'ligneVenteArray' => $ligneVenteArray
        ]);
    }


  
    /**
     * @Route(name="imprimer_facture",path="/facture/imprimer/{id}")
     * @Method({"GET"})
     */
    public function imprimer_facture(Request $request, Vente $vente)
    {

        return $this->render('Vente/imprimer_facture.html.twig',[
            'vente' => $vente,
            // 'client' => $vente->getClient()
        ]);
    }


    /**
     * @Route(name="payer_vente",path="/vente/payer/{id}")
     * @Method({"GET", "POST"})
     */
    public function paiement(Request $request, Vente $vente)
    {
        

        $paiement_form = $this->createFormBuilder()
                                    ->add('montant',IntegerType::class,[
                                           'label' => "Total HT",
                                         'required' => true,
                                   //      'data' => $vente->getResteAPayer() ,
                                        'attr' => [
                                             'min' => 1,
                                             'max' => $vente->getResteAPayer() ,
                                 //           'placeholder' => "Article",
                                 //           'class' => 'form-control',
                                           
                                        ]
                                     ])
                                     ->add('mode',ChoiceType::class,[
                                        'choices'  => [
                                                "Chèque" => 'c' ,
                                                 "Espèce"=> 'e',
                                                 "Virement" =>'v' ,
                                            ],
                                         'required' => true,
                                          'data' => 'e',
                                        'attr' => [
                                 //           'placeholder' => "Article",
                                 //           'class' => 'form-control',
                                           
                                        ]
                                     ]) ;

        if($vente->getClient())
        {
             $paiement_form = $paiement_form->add('client',EntityType::class,[
                                         'label' => "Client",
                                         'required' => false,
                                         'class' => Client::class,
                                         'data' => $vente->getClient(),
                                        'attr' => [
                                 //           'placeholder' => "Article",
                                  //          'class' => 'form-control',
                                           
                                        ]
                                     ]) ;
        }
        else
        {
            $paiement_form = $paiement_form->add('nom', TextType::class, array('label'=>'Nom','attr'=>array('class'=>'form-control','placeholder'=>'Nom du client')))
                        ->add('prenom', TextType::class, array('label'=>'Prenom','attr'=>array('class'=>'form-control','placeholder'=>'Prenom du client')))
                        ->add('raisoc', TextareaType::class, array('label'=>'Raisoc','attr'=>array('class'=>'form-control','placeholder'=>'Raison ')))
                        ->add('adresse', TextareaType::class, array('label'=>'Adresse','attr'=>array('class'=>'form-control','placeholder'=>'Adresse client')))
                        ->add('telephone', TextType::class, array('label'=>'Telephone','attr'=>array('class'=>'form-control','placeholder'=>'Telephone client')))
                        ->add('email', TextType::class, array('label'=>'E-mail','attr'=>array('class'=>'form-control','placeholder'=>'E-mail client')));
            
        }                             

        $paiement_form = $paiement_form->getForm();  

        $paiement_form->handleRequest($request);

        if ( $paiement_form->isSubmitted() && $paiement_form->isValid())  { 

            $paiement = new Paiement();

          
            $em = $this->getDoctrine()->getManager();

            $paiement->setCreatedBy($this->getUser());
            $paiement->setMontant( $paiement_form['montant']->getData() );
            $paiement->setResteAPayer( $vente->getResteAPayer() - $paiement_form['montant']->getData() );
            $paiement->setMode( $paiement_form['mode']->getData() );
            $paiement->setCreatedAt(new \DateTime());
            $paiement->setVente($vente);

         //   $client = null;
            if($vente->getClient())
            {
          //      $client = $vente->getClient(); // $paiement_form['client']->getData() ;
            }            
            else
            {
                $_client = new Client();
                $_client->setNom($paiement_form['nom']->getData());
                $_client->setPrenom($paiement_form['prenom']->getData());
                $_client->setRaisoc($paiement_form['raisoc']->getData());
                $_client->setAdresse($paiement_form['adresse']->getData());
                $_client->setTelephone($paiement_form['telephone']->getData());
                $_client->setEmail($paiement_form['email']->getData());

                $em->persist($_client);

            //    $client = $_client;


                $vente->setClient($_client);

            }

            $vente->setResteAPayer( $vente->getResteAPayer() - $paiement_form['montant']->getData() );
            
            $em->persist($vente);
            $em->persist($paiement);
          
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Paiement enrgistre avec success. ');
            return $this->redirectToRoute('facture_vente',['id'=>$vente->getId()]);

        }

        return $this->render('Vente/paiement.html.twig',[
            'vente' => $vente,
            'client' => $vente->getClient(),
            'paiement_view' => $paiement_form->createView(),

            'resteAPayer' =>  $vente->getResteAPayer() 
        ]);

    }



    private function addArticleToCard($article,  $qte, $pu, $client, $lv = null)
    {
        $card = $this->getCard();             
        $positionArticle = $this->getArticlePositionInCard($article->getId(), $card);

        $article_already_in_card = false;
        $i = 0;

        foreach ($card as $cardArticle) {
            if($cardArticle['id_art'] == $article->getId())
            {
                $card[$i]['qte'] += $qte;
                $card[$i]['pu'] = $pu;
                $card[$i]['idLv'] = $lv;
                $article_already_in_card = true;
                break;
            }
            $i++;
        }

        if(!$article_already_in_card)
        {
            array_push($card, [
                'id_art' => $article->getId(),
                'designation' => $article->getDesignation(),
                'qte' => $qte,
                'pu' => $pu,
                'idLv' => $lv
            ]);
        }



        $session = new Session();
        $session->set('card', $card);

        $session->set('client', $client);
 

        return $card;

    }

    private function getPrixTotalPanier()
    {
         $session = new Session();
         $card = $session->get('card', []);

         $prix = 0;

         foreach ($card as $article) {
             $prix += $article['pu'] * $article['qte'];
         }

         return $prix;
    }


    /*
    * renvoie l'index de l'article dans le panier;
    * renvoie null sinon
    */
    private function getArticlePositionInCard($id_art, $card)
    {
        $index = null;

        $i=0;

      //  var_dump($card[$index]) ; die();

        foreach ($card as $article) {

              //   var_dump($id_art) ; die();
           
            if($article['id_art']==$id_art)
            {
                $index = $i;
                break;
            }
            else
                $i++;
        }


        return $index;

    }

    private function getCard()
    {
        $session = new Session();
        $card = $session->get('card', []);

        return $card;
    }

    private function eraseCard()
    {
        $session = new Session();
        $session->remove('card');
        $session->remove('client');
    }


    /**
     * @Route(name="liste_ventes" ,path="/ventes/{page}", defaults={"page"=1})
     */
    public function listeAction(Request $request, $page)
    {
        // $page = $request->query->getInt('page', 1);

        $em = $this->getDoctrine()->getManager();
        $vente_search_form = $this->createFormBuilder()
                                ->add('client',EntityType::class,[
                                    'label' => "Cilent",
                                     'required' => false,
                                     'class' => Client::class,
                                    'attr' => [
                                        'placeholder' => "Client",
                                        'class' => 'form-control  select2',
                                       
                                    ]
                                ])
                                ->add('dateDeb_search',DateType::class,[
                                    'label' => "Date de début",
                                     'widget' => 'single_text',
                                     'required' => false,
                                     'attr' =>[
                                        'class' => 'form-control'
                                     ]   
                                ])   
                                ->add('dateFin_search',DateType::class,[
                                    'label' => "Date de fin",
                                    // 'data' => (new \DateTime())->format('d/m/Y'),
                                     'widget' => 'single_text',
                                     'required' => false,
                                     'attr' =>[
                                        'class' => 'form-control'
                                    ]   
                                ]) 
                                ->getForm();  

        $vente_search_form->handleRequest($request);

        $venteRepo = $em->getRepository("AppBundle:Vente");

        $ventes =  [];
        $qb = $em->createQueryBuilder();

        $ventesCount = 0;
  
        if ($vente_search_form->isSubmitted() && $vente_search_form->isValid()) {
            $ventes = $venteRepo->getVentes($qb, $page, $vente_search_form['client']->getData(), $vente_search_form["dateDeb_search"]->getData(), $vente_search_form["dateFin_search"]->getData() ); 
            $ventesCount = count($ventes);
        }
        
        $limit = 10;
        $maxPages = ceil($ventesCount / $limit);
        $thisPage = $page;

        return $this->render('Vente/liste.html.twig', array(
            'vente_search_view' => $vente_search_form->createView(),

            'ventes' => $ventes,

            'maxPages' => $maxPages,
            'thisPage' => $thisPage
        ));
    }


    /**
     * @Route(name="stats_ventes" ,path="/statistiques")
     */
    public function statistiquesAction(Request $request)
    {
        $statByCltPage = $request->query->getInt('statByCltPage', 1);
        $statByArtPage = $request->query->getInt('statByArtPage', 1);

        $tab =  $request->query->get('tab', "clt");
      
        $stat_by_clt_form = $this->createFormBuilder()
                                    ->add('client',EntityType::class,[
                                        'label' => "Cilent",
                                         'required' => false,
                                         'class' => Client::class,
                                     //    'choice_label' => 'nom',
                                        'attr' => [
                                            'placeholder' => "Client",
                                            'class' => 'form-control  select2',
                                           
                                        ]
                                     ])
                                     ->add('dateDeb_search',DateType::class,[
                                        'label' => "Date de début",
                                         'widget' => 'single_text',
                                         'required' => false,
                                         'attr' =>[
                                            'class' => 'form-control',
                                         ]   
                                     ])   
                                     ->add('dateFin_search',DateType::class,[
                                        'label' => "Date de fin",
                                         'widget' => 'single_text',
                                         'required' => false,
                                         'attr' =>[
                                            'class' => 'form-control'
                                         ]   
                                     ])   
                                     ->setAction($this->generateUrl('stats_ventes'))
                                     ->getForm();  
  
        $stat_by_clt_form->handleRequest($request);


        $stat_by_article_form = $this->createFormBuilder()
                                    ->add('article',EntityType::class,[
                                        'label' => "Article",
                                         'required' => false,
                                         'class' => Article::class,
                                         'choice_label' => 'designation',
                                        'attr' => [
                                     //       'placeholder' => "Client",
                                    //        'class' => 'form-control  select2',
                                           
                                        ]
                                     ])
                                     ->add('dateDeb_search',DateType::class,[
                                        'label' => "Date de début",
                                         'widget' => 'single_text',
                                         'required' => false,
                                         'attr' =>[
                                            'class' => 'form-control'
                                         ]   
                                     ])   
                                     ->add('dateFin_search',DateType::class,[
                                        'label' => "Date de fin",
                                         'widget' => 'single_text',
                                         'required' => false,
                                         'attr' =>[
                                            'class' => 'form-control'
                                         ]   
                                     ])   
                                     ->setAction($this->generateUrl('stats_ventes'))
                                     ->getForm();  

        $stat_by_article_form->handleRequest($request);

        $venteRepo = $this->getDoctrine()
                          ->getManager()
                          ->getRepository("AppBundle:Vente");

        $stat_by_clt = [] ;
        $statByCltCount = 0;
        if(!$stat_by_clt_form->isSubmitted())
        {
             $stat_by_clt =  $venteRepo->getStatistiquesParClt($statByCltPage,null,null,null, $this->getUser());
             $statByCltCount = $venteRepo->countStatistiquesParClt(null,null,null, $this->getUser());
        }
        else if ($stat_by_clt_form->isSubmitted() && $stat_by_clt_form->isValid()) {
            $tab = "clt";  
            $stat_by_clt =  $venteRepo->getStatistiquesParClt($statByCltPage, $stat_by_clt_form['client']->getData(), $stat_by_clt_form['dateDeb_search']->getData() , $stat_by_clt_form['dateFin_search']->getData(), $this->getUser());

              $statByCltCount = $venteRepo->countStatistiquesParClt( $stat_by_clt_form['client']->getData(),$stat_by_clt_form['dateDeb_search']->getData(), $stat_by_clt_form['dateFin_search']->getData(), $this->getUser());

           //   var_dump($stat_by_clt); die();
        }
        
        $limit = 10;
        $statByCltMaxPages = ceil($statByCltCount / $limit);
        $thisStatByCltPage = $statByCltPage;

        $stat_by_article = [];
        $statByArtCount = 0;
        if(!$stat_by_article_form->isSubmitted())
        {
            $stat_by_article = $venteRepo->getStatistiquesParArt($statByArtPage, null,null,null, $this->getUser());
            $statByArtCount = $venteRepo->countStatistiquesParArt(null,null,null, $this->getUser());
        }
        else if ($stat_by_article_form->isSubmitted() && $stat_by_article_form->isValid()) {
            $tab = "article";  

            $stat_by_article = $venteRepo->getStatistiquesParArt($statByArtPage, $stat_by_article_form['article']->getData(), $stat_by_article_form['dateDeb_search']->getData(), $stat_by_article_form['dateFin_search']->getData(), $this->getUser() );

             $statByArtCount = $venteRepo->countStatistiquesParArt( $stat_by_article_form['article']->getData(), $stat_by_article_form['dateDeb_search']->getData(), $stat_by_article_form['dateFin_search']->getData(), $this->getUser());
        }

       
        $limit = 10;
        $statByArtMaxPages = ceil($statByArtCount / $limit);
        $thisStatByArtPage = $statByArtPage;




        return $this->render('Vente/statistiques.html.twig', array(
            'stat_by_clt_view' => $stat_by_clt_form->createView(),
            'stat_by_clt' => $stat_by_clt,

            'stat_by_article_view' => $stat_by_article_form->createView(),
            'stat_by_article' => $stat_by_article,

            'tab' => $tab,

            'statByCltMaxPages' => $statByCltMaxPages,
            'thisStatByCltPage' => $thisStatByCltPage,

            'statByArtMaxPages' => $statByArtMaxPages,
            'thisStatByArtPage' => $thisStatByArtPage,
        ));
    }



    /**
     * @Route(name="details_vente", path="/vente/details/{id}")
     */
    public function detailAction($id)
    {
        return $this->render('Vente/detail.html.twig', array(
            // ...
        ));
    }

}
