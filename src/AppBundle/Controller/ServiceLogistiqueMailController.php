<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ServiceLogistiqueMail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Servicelogistiquemail controller.
 *
 * @Route("servicelogistiquemail")
 */
class ServiceLogistiqueMailController extends Controller
{
    /**
     * Lists all serviceLogistiqueMail entities.
     *
     * @Route("/", name="servicelogistiquemail_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $serviceLogistiqueMails = $em->getRepository('AppBundle:ServiceLogistiqueMail')->findAll();

        return $this->render('servicelogistiquemail/index.html.twig', array(
            'serviceLogistiqueMails' => $serviceLogistiqueMails,
        ));
    }

    /**
     * Creates a new serviceLogistiqueMail entity.
     *
     * @Route("/new", name="servicelogistiquemail_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $serviceLogistiqueMail = new Servicelogistiquemail();
        $form = $this->createForm('AppBundle\Form\ServiceLogistiqueMailType', $serviceLogistiqueMail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($serviceLogistiqueMail);
            $em->flush();
            $this->addFlash("success", "Mail ajouté avec succès");
            return $this->redirectToRoute('servicelogistiquemail_index');
        }

        return $this->render('servicelogistiquemail/new.html.twig', array(
            'serviceLogistiqueMail' => $serviceLogistiqueMail,
            'form' => $form->createView(),
        ));
    }

   
    /**
     * Displays a form to edit an existing serviceLogistiqueMail entity.
     *
     * @Route("/{id}/edit", name="servicelogistiquemail_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ServiceLogistiqueMail $serviceLogistiqueMail)
    {
        $editForm = $this->createForm('AppBundle\Form\ServiceLogistiqueMailType', $serviceLogistiqueMail);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "Mail modifié avec succès");
            return $this->redirectToRoute('servicelogistiquemail_index');
        }

        return $this->render('servicelogistiquemail/edit.html.twig', array(
            'serviceLogistiqueMail' => $serviceLogistiqueMail,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a serviceLogistiqueMail entity.
     *
     * @Route("/{id}/delete", name="servicelogistiquemail_delete")
     */
    public function deleteAction(Request $request, ServiceLogistiqueMail $serviceLogistiqueMail)
    {
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($serviceLogistiqueMail);
        $em->flush();
        $this->addFlash("success", "Mail supprimé avec succès");
        return $this->redirectToRoute('servicelogistiquemail_index');
    }
}
