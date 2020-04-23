<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Article;
use AppBundle\Entity\LigneVente;
use AppBundle\Entity\Client;
use AppBundle\Entity\Vente;
use AppBundle\Entity\Arrivage;
use AppBundle\Entity\ArticleStock;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 *
 * @Route("backoffice/dashboard")
 */
class DashBoardController extends Controller
{


    /**
     * @Route(name="dashboardIndex",path="/history/{page}", defaults={"page"=0})
     * @Route(name="backoffice",path="/history/{page}", defaults={"page"=0})
     * @Method({"GET","POST"})
     */
	public function indexAction(Request $request, $page)
	{
		// $page = $request->query->getInt('page', 1);
		// $searchBy = $request->query->getInt('tab', "v");
		$session = $this->get('session');
		if($page ==0 ){
			$session->remove('articles');
	    	$session->remove('dateDebutFilterDashboard');
	    	$session->remove('dateFinFilterDashboard');
	    	$session->remove('searchBy');
		}
			$articles = $session->get('articles');
	    	$dateDeb  = $session->get('dateDebutFilterDashboard');
	    	$dateFin  = $session->get('dateFinFilterDashboard');
	    	$searchBy = $session->get('searchBy');			
		


	    
		$em = $this->getDoctrine()->getManager();
		// dump(($articles));
    	// exit;

		$dashboardForm = $this->createFormBuilder();
            $dashboardForm->add('dateDebSearch',DateType::class,['label' => "Date de début",'widget' => 'single_text','required' => true,'attr' =>['class' => 'form-control'], 'data' => $dateDeb])   
                ->add('dateFinSearch',DateType::class,['label' => "Date de fin",'widget' => 'single_text','required' => true,'attr' =>['class' => 'form-control'], 'data' => $dateFin]) 
                  ->add('searchBy',ChoiceType::class,['choices'  => ["Vente" => 'v' ,"Stock"=> 's',"Arrivage" =>'a' ,],'required' => true,'data' => 'v','attr' => ['placeholder' => "Article",], 'data' => $searchBy]);

	        $dashboardForm->add('article',EntityType::class,['multiple' => true,'label' => "Article",'required' => false,'class' => Article::class, 'attr' => [], 'query_builder' => function ($er) {
	                    return $er->createQueryBuilder('a')->orderBy('a.designation', 'ASC');
	                }
	        ]);
	        
	        if(is_array($articles)){
	            if(count($articles) > 0){
	            	foreach ($articles as $key => $art) {
	            		// $selectedArtclesID[] = $art->getId();
	            		$selectedArticles[] = $em->getRepository('AppBundle:Article')->findOneById($art->getId());
	            	}
	            	// $selectedArticles = implode(",", $selectedArtclesID);
	            	// if(!empty($selectedArticles)){

	            	// }
	            	// dump($selectedArticles); exit;
		            $dashboardForm->add('article',EntityType::class,['multiple' => true,'label' => "Article",'required' => false,'class' => Article::class, 'attr' => [], 'data' => $selectedArticles, 'query_builder' => function ($er)  {
		                    return $er->createQueryBuilder('a')
		                    ->orderBy('a.designation', 'ASC');
		                }
		            ]);
	            }
	        }
           
        $dashboardForm = $dashboardForm->getForm();

		$lignesVentes = [];
		$articlesStocks = [];
		$arrivages = [];
		$itemsCount = 0;
		$maxPages = 0;
		$thisPage = 0;

        $dashboardForm->handleRequest($request);


        $articles = $dashboardForm['article']->getData();
    	$dateDeb =  $dashboardForm['dateDebSearch']->getData() ;
    	$dateFin = $dashboardForm['dateFinSearch']->getData();
    	$searchBy = $dashboardForm['searchBy']->getData();

	    if ($dashboardForm->isSubmitted()){
		    if($dashboardForm->isValid()) {    	
		    	$session->set('articles', $articles);
		    	$session->set('dateDebutFilterDashboard', $dateDeb);
		    	$session->set('dateFinFilterDashboard', $dateFin);
		    	$session->set('searchBy', $searchBy);
		    	$page = 1;
		    }
	    }
	    else{
	    	if( $page >= 1){
			    $articles = $session->get('articles');
		    	$dateDeb  = $session->get('dateDebutFilterDashboard');
		    	$dateFin  = $session->get('dateFinFilterDashboard');
		    	$searchBy = $session->get('searchBy'); 
	    	}
	    }

	    if( !empty($dateFin) && !empty($dateDeb) && !empty($searchBy) ){
	    	$articlesIds = [];

	    	foreach ($articles as $article) {
	    		array_push($articlesIds, $article->getId());
	    	}

	    	if($searchBy=="v")
	    	{
	    		$query =  $em->createQueryBuilder()
	    					->select('lv')
               				->from('AppBundle:LigneVente', 'lv')
	    				     ->join('lv.vente','v')
	    				     ->andWhere("v.createdAt BETWEEN :dateDeb AND :dateFin")
	                    ->setParameter('dateDeb',  $dateDeb->format("Y-m-d")." 00:00:00")  
	                    ->setParameter('dateFin', $dateFin->format("Y-m-d")." 23:59:00");

	    		if(!empty($articlesIds))
	    		{
					 $query->andWhere(
					 	$em->createQueryBuilder()->expr()->in('lv.article', $articlesIds)
					 );
	    		}

	    		$lignesVentes = $this->paginate($query, $page);
               					

	    	    $itemsCount = count($query->getQuery()->getResult());		  

	    	}
	    	else if($searchBy=="s")
	    	{
				$query =    $em->createQueryBuilder()
	    					->select('_as')
               				->from('AppBundle:ArticleStock', '_as')
	    				   //  ->join('as.article','a')
	    				     ->andWhere("_as.createdAt BETWEEN :dateDeb AND :dateFin")
		                    ->setParameter('dateDeb',  $dateDeb->format("Y-m-d")." 00:00:00")  
		                    ->setParameter('dateFin', $dateFin->format("Y-m-d")." 23:59:00");

	    		if(!empty($articlesIds))
	    		{
					 $query->andWhere(
					 	$em->createQueryBuilder()->expr()->in('_as.article', $articlesIds)
					 );		
	    		}

	    		$articlesStocks = $this->paginate($query, $page);
               					
	    	    $itemsCount = count($query->getQuery()->getResult());	
	    	   
	    	}
	    	else if($searchBy=="a")
	    	{
	    		
				$query =    $em->createQueryBuilder()
	    					->select('a')
               				->from('AppBundle:Arrivage', 'a')
	    				   //  ->join('as.article','a')
	    				     ->andWhere("a.createdAt BETWEEN :dateDeb AND :dateFin")
		                    ->setParameter('dateDeb',  $dateDeb->format("Y-m-d")." 00:00:00")  
		                    ->setParameter('dateFin', $dateFin->format("Y-m-d")." 23:59:00");

	    		if(!empty($articlesIds))
	    		{
	    				
					 $query->andWhere(
					 	$em->createQueryBuilder()->expr()->in('a.article', $articlesIds)
					 );
	    		}

	    		$arrivages = $this->paginate($query, $page);
               					
	    	    $itemsCount = count($query->getQuery()->getResult());	
	    	}
	    	else
	    	{
	    		throw new \Exception("Critere de recherche non implémente");
	    		
	    	}
		    $limit = 10;
	        $maxPages = ceil($itemsCount / $limit);
	        $thisPage = $page;
	    }


		return $this->render("dashboard/index.html.twig",[
			'dashboardView' => $dashboardForm->createView(),

			'lignesVentes' => $lignesVentes,
			'articlesStocks' => $articlesStocks,
			'arrivages' => $arrivages,

			'maxPages' => $maxPages,
            'thisPage' => $thisPage
		]);
	}



	public function paginate($dql, $page = 1, $limit = 10)
	{
	    $paginator = new Paginator($dql);

	    $paginator->getQuery()
		        ->setFirstResult($limit * ($page - 1)) // Offset
		        ->setMaxResults($limit); // Limit

	    return $paginator;
	}

}
