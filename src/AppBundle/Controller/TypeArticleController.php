<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TypeArticle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Typearticle controller.
 *
 * @Route("backoffice/typearticle")
 */
class TypeArticleController extends Controller
{
    /**
     * Lists all typeArticle entities.
     *
     * @Route("/", name="typearticle_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $typeArticles = $em->getRepository('AppBundle:TypeArticle')->findBy([], ["id" => "desc"]);
//dump($typeArticles); exit;
        return $this->render('typearticle/index.html.twig', array(
            'typeArticles' => $typeArticles,
        ));
    }

    /**
     * Creates a new typeArticle entity.
     *
     * @Route("/new", name="typearticle_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typeArticle = new Typearticle();
        $form = $this->createForm('AppBundle\Form\TypeArticleType', $typeArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $main_path = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . '/photos/';

            $em = $this->getDoctrine()->getManager();

            $prixMin = $form["prixMin"]->getData();
            $prixMax = $form["prixMax"]->getData();

            $typeArticle->setDefaultPrice($prixMin . " - " . $prixMax);

            $em->persist($typeArticle);

            $photo1 = $request->files->get('photo1');
            if ($photo1 != null) {
                $filename = $typeArticle->getId().'_1'.'.'.$photo1->guessExtension();
                if (file_exists($this->container->getParameter('photo_directory').'/'. $filename)) {
                    unlink($this->container->getParameter('photo_directory').'/'. $filename);
                }
                if ($photo1->move($this->container->getParameter('photo_directory'), $filename)) {
                    $typeArticle->setSource1($main_path . $filename);
                }
            }

            $photo2 = $request->files->get('photo2');
            if ($photo2 != null) {
                $filename = $typeArticle->getId().'_2'.'.'.$photo2->guessExtension();
                if (file_exists($this->container->getParameter('photo_directory').'/'. $filename)) {
                    unlink($this->container->getParameter('photo_directory').'/'. $filename);
                }
                if ($photo2->move($this->container->getParameter('photo_directory'), $filename)) {
                    $typeArticle->setSource2($main_path . $filename);
                }
            }

            $photo3 = $request->files->get('photo3');
            if ($photo3 != null) {
                $filename = $typeArticle->getId().'_3'.'.'.$photo3->guessExtension();
                if (file_exists($this->container->getParameter('photo_directory').'/'. $filename)) {
                    unlink($this->container->getParameter('photo_directory').'/'. $filename);
                }
                if ($photo3->move($this->container->getParameter('photo_directory'), $filename)) {
                    $typeArticle->setSource3($main_path . $filename);
                }
            }

            $photo4 = $request->files->get('photo4');
            if ($photo4 != null) {
                $filename = $typeArticle->getId().'_4'.'.'.$photo4->guessExtension();
                if (file_exists($this->container->getParameter('photo_directory').'/'. $filename)) {
                    unlink($this->container->getParameter('photo_directory').'/'. $filename);
                }
                if ($photo4->move($this->container->getParameter('photo_directory'), $filename)) {
                    $typeArticle->setSource4($main_path . $filename);
                }
            }

            $this->addFlash("success", "Type article ajouté avec succès");

            $em->flush();

            return $this->redirectToRoute('typearticle_show', array('id' => $typeArticle->getId()));
        }

        return $this->render('typearticle/new.html.twig', array(
            'typeArticle' => $typeArticle,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a typeArticle entity.
     *
     * @Route("/show/{id}", name="typearticle_show")
     * @Method("GET")
     */
    public function showAction(TypeArticle $typeArticle)
    {

        return $this->render('typearticle/show.html.twig', array(
            'typeArticle' => $typeArticle,
        ));
    }

    /**
     * Finds and displays a typeArticle entity.
     *
     * @Route("/caracteristiques/", name="typearticle_caracteristiques")
     * @Method("POST")
     */
    public function caracteristiquesAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $caracteristiques = $em->createQuery("select c.id, c.libelle " .
                                             "from AppBundle:TypeArticle t " .
                                             "join t.caracteristiques c " .
                                             "where t.id = :id"                                       
                                )
                                ->setParameter("id", $request->request->get('id'))
                                ->getScalarResult();
        return new JsonResponse($caracteristiques);
    }

    /**
     * Displays a form to edit an existing typeArticle entity.
     *
     * @Route("/{id}/edit", name="typearticle_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypeArticle $typeArticle)
    {
        $editForm = $this->createForm('AppBundle\Form\TypeArticleType', $typeArticle);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            $main_path = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . '/photos/';

            $em = $this->getDoctrine()->getManager();

            $prixMin = $editForm["prixMin"]->getData();
            $prixMax = $editForm["prixMax"]->getData();
            if($prixMin != "" or $prixMax != ""){
                $typeArticle->setDefaultPrice($prixMin . " - " . $prixMax);
            }

            $em->persist($typeArticle);

            $photo1 = $request->files->get('photo1');
            if ($photo1 != null) {
                $filename = $typeArticle->getId().'_1'.'.'.$photo1->guessExtension();
                if (file_exists($this->container->getParameter('photo_directory').'/'. $filename)) {
                    unlink($this->container->getParameter('photo_directory').'/'. $filename);
                }
                if ($photo1->move($this->container->getParameter('photo_directory'), $filename)) {
                    $typeArticle->setSource1($main_path . $filename);
                }
            }

            $photo2 = $request->files->get('photo2');
            if ($photo2 != null) {
                $filename = $typeArticle->getId().'_2'.'.'.$photo2->guessExtension();
                if (file_exists($this->container->getParameter('photo_directory').'/'. $filename)) {
                    unlink($this->container->getParameter('photo_directory').'/'. $filename);
                }
                if ($photo2->move($this->container->getParameter('photo_directory'), $filename)) {
                    $typeArticle->setSource2($main_path . $filename);
                }
            }

            $photo3 = $request->files->get('photo3');
            if ($photo3 != null) {
                $filename = $typeArticle->getId().'_3'.'.'.$photo3->guessExtension();
                if (file_exists($this->container->getParameter('photo_directory').'/'. $filename)) {
                    unlink($this->container->getParameter('photo_directory').'/'. $filename);
                }
                if ($photo3->move($this->container->getParameter('photo_directory'), $filename)) {
                    $typeArticle->setSource3($main_path . $filename);
                }
            }

            $photo4 = $request->files->get('photo4');
            if ($photo4 != null) {
                $filename = $typeArticle->getId().'_4'.'.'.$photo4->guessExtension();
                if (file_exists($this->container->getParameter('photo_directory').'/'. $filename)) {
                    unlink($this->container->getParameter('photo_directory').'/'. $filename);
                }
                if ($photo4->move($this->container->getParameter('photo_directory'), $filename)) {
                    $typeArticle->setSource4($main_path . $filename);
                }
            }
            
            $em->flush();

            $this->addFlash("success", "Type article modifié avec succès");
            return $this->redirectToRoute('typearticle_index');
        }

        return $this->render('typearticle/edit.html.twig', array(
            'typeArticle' => $typeArticle,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a typeArticle entity.
     *
     * @Route("/delete/{id}", name="typearticle_delete")
     */
    public function deleteAction(Request $request, TypeArticle $typeArticle)
    {
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeArticle);
        $em->flush();

        $this->addFlash("success", "Type article supprimé avec succès");

        return $this->redirectToRoute('typearticle_index');
    }
}
