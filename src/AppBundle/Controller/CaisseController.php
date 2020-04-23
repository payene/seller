<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use AppBundle\Entity\Vente;
use AppBundle\Entity\Client;



/**
 *
 * @Route("backoffice/caisse")
 */
class CaisseController extends Controller
{
    /**
     * @Route(name="en_cours", path="/en-cours/{page}", defaults={"page"=1})
     */
    public function enCoursAction(Request $request, $page)
    {
        // $page = $request->query->getInt('page', 1);

        $venteRepo = $this->getDoctrine()
                          ->getManager()
                          ->getRepository("AppBundle:Vente");

        $ventes = $venteRepo->getVentesAvecEnCours($page);

        $ventesCount = count($ventes);
    
    //   $totalVentesReturned = $ventes->getIterator()->count();
      //  $totalVentes = $ventes->count();
       // $iterator = $ventes->getIterator();
        $limit = 10;
        $maxPages = ceil($ventesCount / $limit);
        $thisPage = $page;



        return $this->render('caisse/en_cours.html.twig', array(
            'ventes' => $ventes,

            'maxPages' => $maxPages,
            'thisPage' => $thisPage
        ));
    }

    /**
     * @Route(name="rappels_de_caisse", path="/caisse/rappels")
     */
    public function rappelsAction(Request $request)
    {
        //$page = $request->query->getInt('page', 1);
        $fin = new \DateTime();
        $debut = (new \DateTime())->sub(new \DateInterval('P7D'));
        $client = NULL;
    	$venteSearchForm = $this->createFormBuilder()
                ->add('client',EntityType::class,[
                    'label' => "Client",
                     'required' => false,
                     'class' => Client::class,
                    'attr' => [
                        'placeholder' => "Client",
                        'class' => 'form-control  select2',
                       
                    ]
                 ])               
                 ->add('dateDeb',DateType::class,[
                    'label' => "Date de début",
                     'widget' => 'single_text',
                     'required' => false,
                     'data' => $debut,
                     'attr' =>[
                        'class' => 'form-control'
                     ]   
                 ])   
                 ->add('dateFin',DateType::class,[
                    'label' => "Date de fin",
                     'widget' => 'single_text',
                     'data' => $fin,
                     'required' => false,
                     'attr' =>[
                        'class' => 'form-control'
                     ]   
                 ])   
                 ->getForm();  
        $em = $this->getDoctrine()->getManager();
        $venteSearchForm->handleRequest($request);   
        if ($venteSearchForm->isSubmitted()) {
            $client =  $venteSearchForm['client']->getData(); 
            $fin =  $venteSearchForm['dateFin']->getData(); 
            $debut =  $venteSearchForm['dateDeb']->getData(); 

            // if (!$venteSearchForm->isValid()) {

            // }
        }
        
        $query = $em->createQueryBuilder();
        $queryRappel = $query
            ->select('v')
            ->from('AppBundle:Vente', 'v')
            ->join('v.client', 'c')
            ->andwhere("v.createdAt BETWEEN :debut AND :fin ")
            ->setParameter('debut', $debut->format('Y-m-d') . " 00:00:00" )
            ->setParameter('fin', $fin->format('Y-m-d') . " 23:59:59" )
        ;
        if(!empty($client)){
            $queryRappel->andwhere('v.client = :client')->setParameter('client', $client->getId());
        }
        $ventes = $queryRappel->orderBy('v.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;

      

        // 'vente_search_view   ' => $vente_search_form->createView(),
        return $this->render('caisse/rappels.html.twig', array(
            'ventes' => $ventes,
            'venteSearchView' => $venteSearchForm->createView(),
            
        ));
    }

    

}
