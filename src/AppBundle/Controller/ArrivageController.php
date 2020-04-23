<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Arrivage;
use AppBundle\Entity\Stock;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Category;
use AppBundle\Entity\Article;
use AppBundle\Entity\MotifSortieStock;
use AppBundle\Entity\MvtStock;
use AppBundle\Entity\SortieStock;

/**
 * Arrivage controller.
 *
 * @Route("backoffice/arrivage")
 */
class ArrivageController extends Controller
{
    /**
     * Lists all arrivage entities.
     *
     * @Route("/", name="backoffice_arrivage_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $arrivages = $em->getRepository('AppBundle:Arrivage')->findAll();

        return $this->render('arrivage/index.html.twig', array(
            'arrivages' => $arrivages,
        ));
    }

    /**
     * Reguilarize all arrivage entities.
     *
     * @Route("/regularize/stock/nbr", name="backoffice_arrivage_nbr_regularize")
     * @Method("GET")
     */
    public function regularizeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $arrivages = $em->getRepository('AppBundle:Arrivage')->findAll();

        foreach ($arrivages as $key => $arrivage) {
            $article = $em->getRepository('AppBundle:Article')->findOneById($arrivage->getArticle()->getid());

            $query = $em->createQueryBuilder();
            $querySumStock = $query
            ->select('SUM(s.qte) as STOCK')
            ->from('AppBundle:Stock', 's')
            ->join('s.arrivage', 'ar')
            ->andwhere('ar.article = :article')
            ->setParameter('article', $article->getId() )
            ->getQuery()
            ->getSingleResult();

            $nbrStock = $querySumStock['STOCK'];
            $article->setStock($nbrStock);
            $em->merge($article);
            
        }
        $em->flush();
        return $this->redirectToRoute('backoffice_arrivage_new');
       
    }

  /**
     * Reguilarize all arrivage entities.
     *
     * @Route("/regularize/stock/punit", name="backoffice_arrivage_punit_regularize")
     * @Method("GET")
     */
    public function regularizePunitAction()
    {
        $em = $this->getDoctrine()->getManager();
        $arrivages = $em->getRepository('AppBundle:Arrivage')->findAll();

        foreach ($arrivages as $key => $arrivage) {

            $stock = $arrivage->getStock();
            $regulPunit = ($arrivage->getPrixAchat() + $arrivage->getMarge() + $arrivage->getTaxes() + $arrivage->getTva())/$arrivage->getQte();
            $stock->setPunit($regulPunit);
            $em->merge($stock);
            // dump($stock);
        }
        $em->flush();
        // exit;
        return $this->redirectToRoute('backoffice_arrivage_new');
    }

 /**
     * Reguilarize all stoks initial quantity entities.
     *
     * @Route("/regularize/stock/qteinit", name="backoffice_arrivage_qteinit_regularize")
     * @Method("GET")
     */
    public function regularizeQinityAction()
    {
        $em = $this->getDoctrine()->getManager();
        $stocks = $em->getRepository('AppBundle:Stock')->findBy(['qteInit' => 0]);

        foreach ($stocks as $key => $stock) {
            $arrivage = $stock->getArrivage();
            
            $stock->setQteInit($arrivage->getQte());
            $em->merge($stock);
            
        }
        $em->flush();
        return $this->redirectToRoute('backoffice_arrivage_new');
    }

    /**
     * Creates a new arrivage entity.
     *
     * @Route("/new", name="backoffice_arrivage_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $arrivage = new Arrivage();
        $form = $this->createForm('AppBundle\Form\ArrivageType', $arrivage);
        $form->handleRequest($request);

        $page = $request->query->getInt('page', 1);


        
        $arrivage_search = new Arrivage();
     //   $arrivage_search_form = $this->createFormBuilder($arrivage_search)
        $arrivage_search_form = $this->createFormBuilder()
                                    ->add('article',EntityType::class,[
                                        'label' => "Article",
                                        'required' => false,
                                        'class' => Article::class,
                                        'attr' => [
                                            'placeholder' => "Article",
                                            'class' => 'form-control  select2',
                                           
                                        ],
                                        'query_builder' => function ($er) {
                                            return $er->createQueryBuilder('a')
                                                ->orderBy('a.designation', 'ASC')
                                                ;
                                        },
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
                                     ->add('category',EntityType::class,[
                                        'label' => "Categorie",
                                         'required' => false,
                                         'class' => Category::class,
                                         'choice_label' => 'catname',

                                         'attr' =>[
                                            'class' => 'form-control select2'
                                         ],
                                        'query_builder' => function ($er) {
                                            return $er->createQueryBuilder('c')
                                                ->orderBy('c.catname', 'ASC')
                                                ;
                                        },   
                                     ])
                                     ->getForm();  

        $arrivage_search_form->handleRequest($request);


        $arrivageRepo = $this->getDoctrine()
                          ->getManager()
                          ->getRepository("AppBundle:Arrivage");

        $arrivages = [];
        $arrivagesCount = 0;
        $today = new \DateTime();
        $debutMois = new \DateTime("01-". date("m-Y"));
        if(!$arrivage_search_form->isSubmitted())
        {
            $arrivages = $arrivageRepo->getArrivages($page,null,null,$debutMois,$today,   $this->getUser());
            
        } 
        else if ($arrivage_search_form->isSubmitted() && $arrivage_search_form->isValid()) {
             $arrivages = $arrivageRepo->getArrivages($page, $arrivage_search_form['article']->getData(),$arrivage_search_form['category']->getData(),$arrivage_search_form['dateDeb_search']->getData(), $arrivage_search_form['dateFin_search']->getData(), $this->getUser() );

            //$arrivagesCount =  $arrivageRepo->countArrivages($arrivage_search_form['article']->getData(),$arrivage_search_form['category']->getData(),$arrivage_search_form['dateDeb_search']->getData(), $arrivage_search_form['dateFin_search']->getData(), $this->getUser());

        }

        $arrivagesCount =  count($arrivages) ;

        $limit = 3;
        $maxPages = ceil($arrivagesCount / $limit);
        $thisPage = $page;


        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            
            $article = $em->getRepository('AppBundle:Article')->findOneById($arrivage->getArticle()->getId());
            $arrivage->setCreatedBy($this->getUser());
            $arrivage->setCreatedAt(new \DateTime());
           
            $previent  = ($arrivage->getPrixAchat() + $arrivage->getTva() + $arrivage->getTaxes());
            $punit = ($previent + $arrivage->getMarge())/$arrivage->getQte();
            
            $stock = new Stock();
            $stock->setQteInit($arrivage->getQte());
            $stock->setQte($arrivage->getQte());
            $stock->setPunit($punit);
            $stock->setArrivage($arrivage);
            $stock->setCreatedAt(new \DateTime());
            $em->persist($stock);

            $newStock = $article->getStock() + $arrivage->getQte();
            $mvt = new MvtStock();
            $mvt->setQteMvt($arrivage->getQte());
            $mvt->setStock($newStock);
            $mvt->setStockArvg($arrivage->getQte());
            $mvt->setArrivage($arrivage);
            $mvt->setMvtType(1);
            $mvt->setCreatedAt(new \DateTime());
            $mvt->setCreatedBy($this->getUser());
            $em->persist($mvt);

            $article->setStock($newStock);
            $em->merge($article);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Arrivge enrgistre avec success. ');
            return $this->redirectToRoute('backoffice_arrivage_new');
        }


        return $this->render('arrivage/new.html.twig', array(
            'arrivage' => $arrivage,
            'form' => $form->createView(),
        //    'addedToday' => $addedToday,

            'arrivage_search_view' => $arrivage_search_form->createView(),   
            'arrivages' => $arrivages,

            'maxPages' => $maxPages,
            'thisPage' => $thisPage
        ));
    }

    /**
     * Finds and displays a arrivage entity.
     *
     * @Route("/display/{id}", name="backoffice_arrivage_show")
     * @Method("GET")
     */
    public function showAction(Arrivage $arrivage)
    {
        $deleteForm = $this->createDeleteForm($arrivage);

        return $this->render('arrivage/show.html.twig', array(
            'arrivage' => $arrivage,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing arrivage entity.
     *
     * @Route("/edit/{id}", name="backoffice_arrivage_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Arrivage $arrivage)
    {
        /*
        $deleteForm = $this->createDeleteForm($arrivage);
        $editForm = $this->createForm('AppBundle\Form\ArrivageType', $arrivage);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted()) {
            $stock = $arrivage->getStock();
            // dump($stock); exit;
            if( $stock->getQte() != $stock->getQteInit() ){
                $editForm->get('qte')->addError(new formError('Cet arrivage ne peut etre modifie sur cette interface'));
                // return $this->redirectToRoute('backoffice_arrivage_show', array('id' => $arrivage->getId()));
            }

            if ($editForm->isValid()) {
                $arrivage->setUpdatedAt(new \DateTime());
                $stock = $arrivage->getStock();
                $previent  = ($arrivage->getPrixAchat() + $arrivage->getTva() + $arrivage->getTaxes());
                $punit = ($previent + $arrivage->getMarge())/$arrivage->getQte();
                $stock->setQteInit($arrivage->getQte());
                $stock->setQte($arrivage->getQte());
                $stock->setPunit($punit);
                $stock->setArrivage($arrivage);
                

                $article = $em->getRepository('AppBundle:Article')->findOneById($arrivage->getArticle()->getId());

                $newStock = $article->getStock() + $arrivage->getQte();
                $mvt = new MvtStock();
                $mvt->setQteMvt($arrivage->getQte());
                $mvt->setStock($newStock);
                $mvt->setArrivage($arrivage);
                $mvt->setMvtType(2);
                $mvt->setCreatedAt(new \DateTime());
                $mvt->setCreatedBy($this->getUser());
                $em->persist($mvt);


                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("warning", "Ligne arrivage modifié avec succes");
                return $this->redirectToRoute('backoffice_arrivage_new');
            }
              //  return $this->redirectToRoute('backoffice_arrivage_edit', array('id' => $arrivage->getId()));
        }

        return $this->render('arrivage/edit.html.twig', array(
            'arrivage' => $arrivage,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
        */
    }


    /**
     * 
     *
     * @Route("/mvt/stock/sortie/{id}", name="backoffice_arrivage_sortie")
     * @Method({"GET", "POST"})
     */
    public function sortieAction(Request $request, Arrivage $arrivage)
    {
        
        $mvtStockForm = $this->createFormBuilder()
            ->add('qte')
            ->add('motif',EntityType::class,['required' => false,'class' => MotifSortieStock::class, 'choice_label' => 'motif','attr' => ['class' => 'form-control  select2',]])
             ->getForm();  


        $mvtStockForm->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $qteSortie = $mvtStockForm->get('qte')->getData();
        $motif = $mvtStockForm->get('motif')->getData();



        // $test = $em->getRepository('AppBundle:MvtStock')->findBy([], ['qteMvt' => 'DESC']);
        // dump($test); exit;

        if ($mvtStockForm->isSubmitted()) {
            $stock = $arrivage->getStock();
            $article = $em->getRepository('AppBundle:Article')->findOneById($arrivage->getArticle()->getId());
        //     // dump($stock); exit;
            if( $stock->getQte() < $qteSortie ){
                $mvtStockForm->get('qte')->addError(new formError('Quantite incorrecte'));
                // return $this->redirectToRoute('backoffice_arrivage_show', array('id' => $arrivage->getId()));
            }
            if( empty($motif) ){
                $mvtStockForm->get('motif')->addError(new formError('Motif obligatoire'));
                // return $this->redirectToRoute('backoffice_arrivage_show', array('id' => $arrivage->getId()));
            }

            if ($mvtStockForm->isValid()) {

                $newStockArrivage = $stock->getQte() - $qteSortie;
                $newStockArticle = $article->getStock() - $qteSortie;
                
                $stock->setQte($newStockArrivage);
                $article->setStock($newStockArticle);
                $em->merge($article);
                $em->merge($stock);

                $sortieStock = new SortieStock();
                $sortieStock->setQteSortie($qteSortie);
                $sortieStock->setArrivage($arrivage);
                $sortieStock->setMotifSortie($motif);
                $sortieStock->setCreatedAt(new \DateTime());
                $sortieStock->setCreatedBy($this->getUser());

                $mvt = new MvtStock();
                $mvt->setQteMvt($qteSortie);
                $mvt->setSortieStock($sortieStock);
                $mvt->setArrivage($arrivage);
                $mvt->setStockArvg($newStockArrivage);
                $mvt->setStock($newStockArticle);
                $mvt->setMvtType(2);
                $mvt->setCreatedAt(new \DateTime());
                $mvt->setCreatedBy($this->getUser());
                $em->persist($mvt);
                
                $em->flush();

                $this->addFlash("success", "Sortie enregistree avec success");
                return $this->redirectToRoute('backoffice_arrivage_sortie' , ['id' => $arrivage->getid()]);
            }
        //       //  return $this->redirectToRoute('backoffice_arrivage_edit', array('id' => $arrivage->getId()));
        }

        return $this->render('arrivage/mvt.sortie.html.twig', array(
            'arrivage' => $arrivage,
            'mvtStockForm' => $mvtStockForm->createView(),
        ));
    }

    /**
     * Deletes a arrivage entity.
     *
     * @Route("/remove/{id}", name="backoffice_arrivage_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Arrivage $arrivage)
    {
        $form = $this->createDeleteForm($arrivage);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted()) {
            $stock = $arrivage->getStock();
            $stock = $em->getRepository('AppBundle:Stock')->findOneByid($stock->getId());
            // dump($stock); exit;
            if( $stock->getQte() != $stock->getQteInit() ){
                $this->addFlash("danger", "Cette ligne arrivage ne peut etre modifiée sur cette interface");
                return $this->redirectToRoute('backoffice_arrivage_show', array('id' => $arrivage->getId()));
            }
            $article = $em->getRepository('AppBundle:Article')->findOneById($arrivage->getArticle()->getId());
            $mvtStock = $em->getRepository('AppBundle:MvtStock')->findOneBy(['arrivage' => $arrivage->getId()]);
            // dump($arrivage->getId()); exit;
            $newStockArticle = $article->getStock() - $stock->getQte();
            if ($form->isValid()) {
                $em->remove($stock);
                $em->remove($arrivage);
                $em->remove($mvtStock);
                $article->setStock($newStockArticle);
                $em->merge($article);
                $em->flush();
                $this->addFlash("warning", "Ligne arrivage supprimee avec succes");
            }
        }
        return $this->redirectToRoute('backoffice_arrivage_new');
    }

    /**
     * Creates a form to delete a arrivage entity.
     *
     * @param Arrivage $arrivage The arrivage entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Arrivage $arrivage)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backoffice_arrivage_delete', array('id' => $arrivage->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}
