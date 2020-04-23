<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Proformat;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Proformat controller.
 *
 * @Route("fontoffice/proformat")
 */
class ProformatController extends Controller
{
    /**
     * Lists all proformat entities.
     *
     * @Route("/", name="proformat_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $proformats = $em->getRepository('AppBundle:Proformat')->findAll();

        return $this->render('proformat/index.html.twig', array(
            'proformats' => $proformats,
        ));
    }

    /**
     * Creates a new proformat entity.
     *
     * @Route("/new", name="proformat_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $proformat = new Proformat();
        $form = $this->createForm('AppBundle\Form\ProformatType', $proformat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($proformat);
            $em->flush();

            return $this->redirectToRoute('proformat_show', array('id' => $proformat->getId()));
        }

        return $this->render('proformat/new.html.twig', array(
            'proformat' => $proformat,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a proformat entity.
     *
     * @Route("/{id}/proformatShow", name="proformat_show")
     * @Method("GET")
     */
    public function showAction(Proformat $proformat)
    {
        $deleteForm = $this->createDeleteForm($proformat);

        return $this->render('proformat/show.html.twig', array(
            'proformat' => $proformat,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing proformat entity.
     *
     * @Route("/{id}/edit", name="proformat_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Proformat $proformat)
    {
        $deleteForm = $this->createDeleteForm($proformat);
        $editForm = $this->createForm('AppBundle\Form\ProformatType', $proformat);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('proformat_edit', array('id' => $proformat->getId()));
        }

        return $this->render('proformat/edit.html.twig', array(
            'proformat' => $proformat,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a proformat entity.
     *
     * @Route("/{id}", name="proformat_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Proformat $proformat)
    {
        $form = $this->createDeleteForm($proformat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($proformat);
            $em->flush();
        }

        return $this->redirectToRoute('proformat_index');
    }

    /**
     * Creates a form to delete a proformat entity.
     *
     * @param Proformat $proformat The proformat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Proformat $proformat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('proformat_delete', array('id' => $proformat->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
