<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Article controller.
 *
 * @Route("user")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('UserBundle:Default:index.html.twig');
    }

    /**
     * Lists all user entities.
     *
     * @Route("/userList", name="user_list")
     * @Method("GET")
     */
    public function userListAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('UserBundle:User')->findAll();

        return $this->render('user/user_list.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Create a user entities.
     *
     * @Route("/userNew", name="user_new")
     * @Method("GET")
     */
    public function userNewAction()
    {
        $em = $this->getDoctrine()->getManager();

      /*  
        $user_form = $this->createFormBuilder(new User())
        				  ->add()
*/
        return $this->render('@User/Default/new_user.html.twig', array(
            
        ));
    }

    /**
     * Create a user entities.
     *
     * @Route("/userShow", name="user_show")
     * @Method("GET")
     */
    public function userShowAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('UserBundle:User')->findAll();

        return $this->render('user/user_list.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Create a user entities.
     *
     * @Route("/{id}/userEdit", name="user_edit")
     * @Method("GET")
     */
    public function userEditAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('UserBundle:User')->findAll();

        return $this->render('user/user_list.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Create a user entities.
     *
     * @Route("/{id}/userDelete", name="user_delete")
     * @Method("GET")
     */
    public function userDeleteAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('UserBundle:User')->findAll();

        return $this->render('user/user_list.html.twig', array(
            'users' => $users,
        ));
    }
}
