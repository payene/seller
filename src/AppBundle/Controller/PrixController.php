<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Prix;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Prix controller.
 *
 * @Route("backoffice/prix")
 */
class PrixController extends Controller
{
    /**
     * Lists all prix entities.
     *
     * @Route("/", name="prix_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $prixes = $em->getRepository('AppBundle:Prix')->findAll();

        return $this->render('prix/index.html.twig', array(
            'prixes' => $prixes,
        ));
    }

    /**
     * Creates a new prix entity.
     *
     * @Route("/new", name="prix_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $prix = new Prix();
        $form = $this->createForm('AppBundle\Form\PrixType', $prix);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($prix);
            $em->flush();

            return $this->redirectToRoute('prix_show', array('id' => $prix->getId()));
        }

        return $this->render('prix/new.html.twig', array(
            'prix' => $prix,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a prix entity.
     *
     * @Route("/{id}", name="prix_show")
     * @Method("GET")
     */
    public function showAction(Prix $prix)
    {
        $deleteForm = $this->createDeleteForm($prix);

        return $this->render('prix/show.html.twig', array(
            'prix' => $prix,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing prix entity.
     *
     * @Route("/{id}/edit", name="prix_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Prix $prix)
    {
        $deleteForm = $this->createDeleteForm($prix);
        $editForm = $this->createForm('AppBundle\Form\PrixType', $prix);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('prix_edit', array('id' => $prix->getId()));
        }

        return $this->render('prix/edit.html.twig', array(
            'prix' => $prix,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a prix entity.
     *
     * @Route("/{id}", name="prix_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Prix $prix)
    {
        $form = $this->createDeleteForm($prix);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($prix);
            $em->flush();
        }

        return $this->redirectToRoute('prix_index');
    }

    /**
     * Creates a form to delete a prix entity.
     *
     * @param Prix $prix The prix entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Prix $prix)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('prix_delete', array('id' => $prix->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
