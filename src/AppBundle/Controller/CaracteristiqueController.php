<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Caracteristique;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Caracteristique controller.
 *
 * @Route("backoffice/caracteristique")
 */
class CaracteristiqueController extends Controller
{
    /**
     * Lists all caracteristique entities.
     *
     * @Route("/", name="caracteristique_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $caracteristiques = $em->getRepository('AppBundle:Caracteristique')->findAll();

        return $this->render('caracteristique/index.html.twig', array(
            'caracteristiques' => $caracteristiques,
        ));
    }

    /**
     * Creates a new caracteristique entity.
     *
     * @Route("/new", name="caracteristique_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $caracteristique = new Caracteristique();
        $form = $this->createForm('AppBundle\Form\CaracteristiqueType', $caracteristique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $autre = $em->getRepository("AppBundle:Caracteristique")->findOneByLibelle($caracteristique->getLibelle());
            if($autre != null){
                $this->addFlash("danger", "Erreur: Cette caractéristique existe déjà");
            }
            else{
                $em->persist($caracteristique);
                $em->flush();
                $this->addFlash("success", "Caractéristique ajoutée avec succès");
                return $this->redirectToRoute('caracteristique_index');
            }
        }

        return $this->render('caracteristique/new.html.twig', array(
            'caracteristique' => $caracteristique,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing caracteristique entity.
     *
     * @Route("/{id}/edit", name="caracteristique_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Caracteristique $caracteristique)
    {
        $editForm = $this->createForm('AppBundle\Form\CaracteristiqueType', $caracteristique);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $autre = $em->getRepository("AppBundle:Caracteristique")->findOneByLibelle($caracteristique->getLibelle());
            if($autre != null){
                $this->addFlash("danger", "Erreur: Cette caractéristique existe déjà");
            }
            else{
                $em->persist($caracteristique);
                $em->flush();
                $this->addFlash("success", "Caractéristique modifiée avec succès");
                return $this->redirectToRoute('caracteristique_index');
            }
            
        }

        return $this->render('caracteristique/edit.html.twig', array(
            'caracteristique' => $caracteristique,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a caracteristique entity.
     *
     * @Route("/delete/{id}", name="caracteristique_delete")
     */
    public function deleteAction(Request $request, Caracteristique $caracteristique)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($caracteristique);
        $em->flush();
        $this->addFlash("success", "caracteristique supprimée avec succès");
        return $this->redirectToRoute('caracteristique_index');
    }
}
