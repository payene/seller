<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Doctrine\ORM\EntityRepository;
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
use AppBundle\Entity\Inventaire;
use AppBundle\Entity\InventaireArticle;



/**
 *
 * @Route("backoffice/inventaire")
 */
class InventaireController extends Controller
{

    /**
     * @Route(name="listeInventaire",path="/inventaire/liste")
     * @Method({"GET"})
     **/
    public function indexAction(Request $request)
    {
        
        $page = $request->query->getInt('page', 1);

        $inventaireRepo = $this->getDoctrine()
                          ->getManager()
                          ->getRepository("AppBundle:Inventaire");

        $inventaires = $inventaireRepo->getInventaires($page);

        $inventairesCount = $inventaireRepo->countInventaires();
    
    //   $totalVentesReturned = $ventes->getIterator()->count();
      //  $totalVentes = $ventes->count();
       // $iterator = $ventes->getIterator();
        $limit = 10;
        $maxPages = ceil($inventairesCount / $limit);
        $thisPage = $page;

        $inventaireEnCours = $this->inventaireEstEnCours();
        
        return $this->render('inventaire/index.html.twig',[
            'inventaires' => $inventaires,
            'inventaireEnCours' => $inventaireEnCours,

            'maxPages' => $maxPages,
            'thisPage' => $thisPage
        ]);
    }

    private function inventaireEstEnCours()
    {
        $inventaireEnCours = false;
        $inventaireCourant =  $this->getDoctrine()
                             ->getManager()
                             ->createQueryBuilder()
                             ->select('i')
                             ->from('AppBundle:Inventaire', 'i')
                             ->where("i.dateFin IS NULL ")
                             ->getQuery()
                             ->getOneOrNullResult();

        if($inventaireCourant)
            $inventaireEnCours = true;

        return $inventaireEnCours;
    }

    /**
     * @Route(name="detailInventaire",path="/inventaire/detail/{id}")
     * @Method({"GET"})
     **/
    public function details(Request $request, Inventaire $inventaire)
    {
        
        return $this->render('inventaire/details.html.twig',[
            'inventaire' => $inventaire
        ]);
    }



    /**
     * @Route(name="demarrerInventaire",path="/inventaire/demarrer")
     * @Method({"GET"})
     **/
    public function demarrerAction(Request $request)
    {
      //  dump($this->inventaireEstEnCours()); die();
        if($this->inventaireEstEnCours())
        {
            return $this->redirectToRoute('listeInventaire');
        }

    	$inventaire = new Inventaire();

    	//enregistrer dans la table inventaire la date de début
    	$em = $this->getDoctrine()->getManager();
    	$inventaire->setDateDeb(new \DateTime());

        $em->persist($inventaire);
        $em->flush();

    	return $this->redirectToRoute('enregistrerInventaire',['id'=>$inventaire->getId()]);
    }

 	/**
     * @Route(name="enregistrerInventaire",path="/inventaire/enregistrer/{id}")
     * @Method({"GET","POST"})
    **/
    public function enregistrerAction(Request $request, Inventaire $inventaire)
    {
        $em = $this->getDoctrine()->getManager();

    	$inventairesArticles = $em->createQueryBuilder()
	                             ->select('ia')
	                             ->from('AppBundle:InventaireArticle', 'ia')
	                             ->where('ia.inventaire = :inventaire')
	                              ->setParameter('inventaire', $inventaire)
	                             ->getQuery()
                                ->getResult(); 

        $articlesInventoriesId = [];
        foreach ($inventairesArticles as $ia) {
            array_push($articlesInventoriesId, $ia->getId());
        }                        


        $inventaireForm = $this->createFormBuilder()
                                    ->add('article',EntityType::class,[
                                        'label' => "Article",
                                         'required' => false,
                                         'class' => Article::class,
                                         'query_builder' => function (EntityRepository $er) use ($em, $articlesInventoriesId) {
                                            if(empty($articlesInventoriesId))
                                            {
                                                return $er->createQueryBuilder('a');
                                            }

									        return $er->createQueryBuilder('a')
									        		  ->where($em->createQueryBuilder()->expr()->notIn('a.id', $articlesInventoriesId));	
									        		  
									    },
                                        'attr' => [
                                 //           'placeholder' => "Article",
                                 //           'class' => 'form-control',
                                           
                                        ]
                                     ])
                                    ->add('qte',IntegerType::class,[ 
                                        'required' => false,
                                     //   'data' => 1,
                                        'attr' => [
                                         //   'min' => 1,
                                        ]
                                    ])
                                    ->getForm();  
                                    
        $inventaireForm->handleRequest($request);

        if ( $inventaireForm->isSubmitted() && $inventaireForm->isValid())  { 
 
            $inventaireArticle = new inventaireArticle();
            $inventaireArticle->setQte($inventaireForm['qte']->getData());
            $inventaireArticle->setArticle($inventaireForm['article']->getData());
            $inventaireArticle->setInventaire($inventaire);

            $stockLogiciel = 0;

            //récupération du dernier stock de l'article
            $articleStock = $this->getDoctrine()
                                 ->getManager()
                                 ->createQueryBuilder()
                                 ->select('as')
                                 ->from('AppBundle:ArticleStock','as')
                                 ->where('as.article = :article')
                                 ->setParameter('article', $inventaireForm['article']->getData())
                                 ->orderBy('as.id', 'desc')
                                 ->setMaxResults(1)
                                 ->getFirstResult();
                          //       ->getOneOrNullResult();
                                // ->getResult();

          //  if($articleStock && isset($articleStock->qte))
                          //       dump($articleStock); 
            if(!empty($articleStock))
            {
                $stockLogiciel = $articleStock->qte;       
                                 
            }
         //      dump($stockLogiciel); 
            $inventaireArticle->setStockLogiciel($stockLogiciel);

      //      dump($inventaireArticle);
            
            $em->persist($inventaireArticle);
            $em->flush();

            return $this->redirectToRoute('enregistrerInventaire',['id'=>$inventaire->getId()]);
        }

        return $this->render("inventaire/enregistrer.html.twig",[
        	'inventaireView' => $inventaireForm->createView(),

        	'inventairesArticles' => $inventairesArticles,
        	'inventaire' => $inventaire
        ]);

    }

	/**
     * @Route(name="cloturerInventaire",path="/inventaire/cloturer/{id}")
     * @Method({"GET"})
     **/
    public function cloturerAction(Request $request, Inventaire $inventaire)
    {
    	$inventaire =   $this->getDoctrine()
    						 ->getRepository('AppBundle:Inventaire')->find($inventaire);

    	$inventaire->setDateFin(new \DateTime());

    	$em = $this->getDoctrine()->getManager();
 		$em->persist($inventaire);
        $em->flush();
		
		return $this->redirectToRoute('listeInventaire');
    }

    
}
