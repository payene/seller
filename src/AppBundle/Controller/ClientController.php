<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\Prix;
use AppBundle\Entity\Suscriber;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

/**
 * Client controller.
 *
 * @Route("backoffice/client")
 */
class ClientController extends Controller
{
    /**
     * Lists all client entities.
     *
     * @Route("/clientList", name="client_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $clients = $em->getRepository('AppBundle:Client')->findAll();

        return $this->render('client/list.html.twig', array(
            'clients' => $clients,
        ));
    }

    /**
     * Creates a new client entity.
     *
     * @Route("/new", name="client_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $client = new Client();
        $form = $this->createForm('AppBundle\Form\ClientType', $client);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $userManager = $this->get('fos_user.user_manager');
            $email = $client->getEmail();
            $username = $client->getTelephone();
            $password = uniqid();
            $email_exist = $userManager->findUserByEmail($email);
            $username_exist = $userManager->findUserByEmail($username);
            
            // Check if the user exists to prevent Integrity constraint violation error in the insertion
            if($email_exist){
                $form->get('email')->addError(new FormError('Cette adresse e-mail n\'est pas disponible'));
            }
            if($username_exist){
                $form->get('telephone')->addError(new FormError('Ce numero de telephone n\'est pas disponible'));
            }

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                //Enregistrement du client
                //$em->persist($client);
                //Enregistrement d'un subscriber
                $subscriber = new Suscriber();
                $subscriber->setCreatedAt(new \DateTime());
                $subscriber->setClient($client);            
                //$em->persist($subscriber);

                //Enregistrement d'un User
                $user = $userManager->createUser();
                $user->setUsername($username);
                $user->setEmail($email);
                $user->setEmailCanonical($email);
                $user->setEnabled(1); // enable the user or enable it later with a confirmation token in the email
                $user->setPlainPassword($password);
                $user->setSuscriber($subscriber);
                $userManager->updateUser($user);            

                $em->flush();

                return $this->redirectToRoute('client_show', array('id' => $client->getId()));
            }
        }

        return $this->render('client/new.html.twig', array(
            'client' => $client,
            'form' => $form->createView(),
        ));
    }


    /**
     * Creates a new client entity.
     *
     * @Route("/priceAttribution", name="price_attribution")
     * @Method({"GET", "POST"})
     */
    public function priceAttributionAction(Request $request)
    {
        $prix = new Prix();
        $form = $this->createForm('AppBundle\Form\PrixType', $prix);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


        $data = $form->getData();

        $prix->setDateDebut(new \DateTime($data->getDateDebut()));
        $prix->setDateFin(new \DateTime($data->getDateFin()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($prix);
            $em->flush();

            return $this->redirectToRoute('price_attribution');
        }

        return $this->render('client/attribution.html.twig', array(
            'prix' => $prix,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a client entity.
     *
     * @Route("/{id}", name="client_show")
     * @Method("GET")
     */
    public function showAction(Client $client)
    {
        //$deleteForm = $this->createDeleteForm($client);

        return $this->render('client/show.html.twig', array(
            'client' => $client,
            //'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing client entity.
     *
     * @Route("/{id}/edit", name="client_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Client $client)
    {
        //$deleteForm = $this->createDeleteForm($client);
        $form = $this->createForm('AppBundle\Form\ClientType', $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_show', array('id' => $client->getId()));
        }

        return $this->render('client/edit.html.twig', array(
            'client' => $client,
            'form' => $form->createView(),
            //'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a client entity.
     *
     * @Route("/{id}/clientDelete", name="client_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, Client $client)
    {

        $em = $this->getDoctrine()->getManager();
            $em->remove($client);
            $em->flush();

        return $this->redirectToRoute('client_list');
    }

}
