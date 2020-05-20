<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Valeur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\CategoryType;
use AppBundle\Form\ArticleType;
use AppBundle\Form\ArticleSearchType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\FormError;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Article controller.
 *
 * @Route("backoffice/article")
 */
class ArticleController extends Controller
{
    /**
     * Lists all article entities.
     *
     * @Route("/articleList", name="article_list")
     * @Method({"GET", "POST"})
     */
    public function articleListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $artcilesArray = [];
        // $categories = $em->getRepository('AppBundle:Category')->findAll();

        $article = new Article();
        $formSearch = $this->createForm('AppBundle\Form\ArticleSearchType', $article);
        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted()){
            $priceMax = intval($formSearch['priceMax']->getData());
            $priceMin = intval($formSearch['priceMin']->getData());
            $stockMax = intval($formSearch['stockMax']->getData());
            $stockMin = intval($formSearch['stockMin']->getData());
// 
            // dump($priceMin);
            // dump($priceMax);

            if ($priceMax < $priceMin) {
                $formSearch->get('priceMin')->addError(new FormError('Veuillez entrer un prix minimal inferieur au prix maximal'));
            }
            if ($stockMax < $stockMin) {
                $formSearch->get('stockMin')->addError(new FormError('Veuillez entrer un stock minimal inferieur au stock maximal'));
            }
           
            if($formSearch->isValid()) {

                
                echo    $artName = $article->getDesignation();
                $typeArticle = $article->getTypeArticle();

                $query = $em->createQueryBuilder();
                 $artQuery = $query
                        ->select('a')
                        ->from('AppBundle:Article', 'a')
                        ->join('a.typeArticle', 't')
                        ->where("a.designation LIKE  :artName")
                        ->setParameter('artName', "%$artName%");
                        

                        if(!empty($typeArticle)){
                            $query->andwhere("t.id = :type ")
                            ->setParameter('type', $typeArticle->getId());
                        }

                        
                        if($priceMin >= 0 ){
                            $artQuery->andwhere("a.defaultPrice >= :pmin ")
                            ->setParameter('pmin', $priceMin);
                        }
                        if($priceMax > 0 ){
                            $artQuery->andwhere("a.defaultPrice <= :pmax ")
                            ->setParameter('pmax', $priceMax);
                        }

                        if($stockMin >= 0 ){
                            $artQuery->andwhere("a.stock >= :stockmin ")
                            ->setParameter('stockmin', $stockMin);
                        }
                        if($stockMax > 0 ){
                            $artQuery->andwhere("a.stock <= :stockmax ")
                            ->setParameter('stockmax', $stockMax);
                        }

                        $artcilesArray = $artQuery->orderBy('a.designation', 'ASC')
                        ->getQuery()->getResult();
            }

        }

        return $this->render('article/article_list.html.twig', array(
            'articles' => $artcilesArray,
            'formSearch' => $formSearch->createView(),
        ));
    }

    /**
     * Creates a new article entity.
     *
     * @Route("/articleNew", name="article_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm('AppBundle\Form\ArticleType', $article);
        $form->handleRequest($request);
        //die($this->container->getParameter('photo_directory'));
        $main_path = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . '/photos/';
//dump($request->request); exit;
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $article->setCategory($article->getTypeArticle()->getCategory());
            $em->persist($article);

            $photo1 = $request->files->get('photo1');
            if ($photo1 != null) {
                $filename = $article->getId().'_1'.'.'.$photo1->guessExtension();
                if (file_exists($this->container->getParameter('photo_directory').'/'. $filename)) {
                    unlink($this->container->getParameter('photo_directory').'/'. $filename);
                }
                if ($photo1->move($this->container->getParameter('photo_directory'), $filename)) {
                    $article->setSource1($main_path . $filename);
                }
            }

            $photo2 = $request->files->get('photo2');
            if ($photo2 != null) {
                $filename = $article->getId().'_2'.'.'.$photo2->guessExtension();
                if (file_exists($this->container->getParameter('photo_directory').'/'. $filename)) {
                    unlink($this->container->getParameter('photo_directory').'/'. $filename);
                }
                if ($photo2->move($this->container->getParameter('photo_directory'), $filename)) {
                    $article->setSource2($main_path . $filename);
                }
            }

            $photo3 = $request->files->get('photo3');
            if ($photo3 != null) {
                $filename = $article->getId().'_3'.'.'.$photo3->guessExtension();
                if (file_exists($this->container->getParameter('photo_directory').'/'. $filename)) {
                    unlink($this->container->getParameter('photo_directory').'/'. $filename);
                }
                if ($photo3->move($this->container->getParameter('photo_directory'), $filename)) {
                    $article->setSource3($main_path . $filename);
                }
            }

            $photo4 = $request->files->get('photo4');
            if ($photo4 != null) {
                $filename = $article->getId().'_4'.'.'.$photo4->guessExtension();
                if (file_exists($this->container->getParameter('photo_directory').'/'. $filename)) {
                    unlink($this->container->getParameter('photo_directory').'/'. $filename);
                }
                if ($photo4->move($this->container->getParameter('photo_directory'), $filename)) {
                    $article->setSource4($main_path . $filename);
                }
            }


            $em->flush();

            $caracteristiques = $article->getTypeArticle()->getCaracteristiques();
            foreach($caracteristiques as $caracteristique){
                $valeur = new Valeur();
                $valeur->setArticle($article);
                $valeur->setCaracteristique($caracteristique);
                $valeur->setValeurCaracteristique( $request->request->get("val_" . $caracteristique->getId()) );
                $em->persist($valeur);
                $em->flush();
            }

            return $this->redirectToRoute('article-show', array('id' => $article->getId()));
        }

        return $this->render('article/article-new.html.twig', array(
            'article' => $article,
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a article entity.
     *
     * @Route("/{id}/article-show", name="article-show")
     * @Method("GET")
     */
    public function article_showAction(Article $article)
    {
        $deleteForm = $this->createDeleteForm($article);

        return $this->render('article/article_show.html.twig', array(
            'article' => $article,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing article entity.
     *
     * @Route("/{id}/articleEdit", name="article_edit")
     * @Method({"GET", "POST"})
     */
    public function article_editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->findOneById($id);
        $editForm = $this->createForm('AppBundle\Form\ArticleType', $article);
        $editForm->handleRequest($request);
        $main_path = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . '/photos/';

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $article->setCategory($article->getTypeArticle()->getCategory());
            //Upload des fichiers
            $photo1 = $request->files->get('photo1');
            if ($photo1 != null) {
                $filename = $article->getId().'_1'.'.'.$photo1->guessExtension();
                if (file_exists($this->container->getParameter('photo_directory').'/'. $filename)) {
                    unlink($this->container->getParameter('photo_directory').'/'. $filename);
                }
                if ($photo1->move($this->container->getParameter('photo_directory'), $filename)) {
                    $article->setSource1($main_path . $filename);
                }
            }

            $photo2 = $request->files->get('photo2');
            if ($photo2 != null) {
                $filename = $article->getId().'_2'.'.'.$photo2->guessExtension();
                if (file_exists($this->container->getParameter('photo_directory').'/'. $filename)) {
                    unlink($this->container->getParameter('photo_directory').'/'. $filename);
                }
                if ($photo2->move($this->container->getParameter('photo_directory'), $filename)) {
                    $article->setSource2($main_path . $filename);
                }
            }

            $photo3 = $request->files->get('photo3');
            if ($photo3 != null) {
                $filename = $article->getId().'_3'.'.'.$photo3->guessExtension();
                if (file_exists($this->container->getParameter('photo_directory').'/'. $filename)) {
                    unlink($this->container->getParameter('photo_directory').'/'. $filename);
                }
                if ($photo3->move($this->container->getParameter('photo_directory'), $filename)) {
                    $article->setSource3($main_path . $filename);
                }
            }

            $photo4 = $request->files->get('photo4');
            if ($photo4 != null) {
                $filename = $article->getId().'_4'.'.'.$photo4->guessExtension();
                if (file_exists($this->container->getParameter('photo_directory').'/'. $filename)) {
                    unlink($this->container->getParameter('photo_directory').'/'. $filename);
                }
                if ($photo4->move($this->container->getParameter('photo_directory'), $filename)) {
                    $article->setSource4($main_path . $filename);
                }
            }
            
            $this->getDoctrine()->getManager()->flush();
            
            $caracteristiques = $article->getTypeArticle()->getCaracteristiques();
            foreach($caracteristiques as $caracteristique){
                $valeur = $em->getRepository("AppBundle:Valeur")->findOneBy(["article" => $article, "caracteristique" => $caracteristique]);
                if($valeur == null){
                    $valeur = new Valeur();
                    $valeur->setArticle($article);
                    $valeur->setCaracteristique($caracteristique);
                }
                $valeur->setValeurCaracteristique( $request->request->get("val_" . $caracteristique->getId()) );
                $em->persist($valeur);
                $em->flush();
            }
            $this->addFlash("success", "Modification réussie!");
            return $this->redirectToRoute('article-show', array('id' => $article->getId()));
        }

        return $this->render('article/article_edit.html.twig', array(
            'article' => $article,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a article entity.
     *
     * @Route("/{id}/articleDelete", name="article_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
            $em->flush();

        return $this->redirectToRoute('article_list');
    }

    /**
     * Creates a form to delete a article entity.
     *
     * @param Article $article The article entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Article $article)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('article_delete', array('id' => $article->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Deletes a article entity.
     *
     * @Route("/arrivage_new", name="arrivage_new")
     * @Method("GET")
     */
    public function arrivageNewAction(Request $request, Article $article)
    {
        $data = array(
        'value' => null,
        'number' => 10,
        'string' => 'No value',
        );
        $form = $this->createFormBuilder()
                 ->add('value', TextType::class, array('required' => false))
                 ->add('number', NumberType::class)
                 ->add('string', TextType::class)
                 ->add('save', SubmitType::class)
                 ->getForm();
    
        $form->handleRequest($request);

        return $this->render('article/arrivage-new.html.twig', array(
            //'article' => $article,
            'form' => $form->createView(),
        ));
    }
}
