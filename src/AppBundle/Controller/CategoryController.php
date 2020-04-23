<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

/**
 * Category controller.
 *
 * @Route("backoffice/category")
 */
class CategoryController extends Controller
{
    /**
     * Lists all category entities.
     *
     * @Route("/categoryList", name="category_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Category')->getCategoryList();
        $rayons = $em->getRepository('AppBundle:Category')->getRayonsList();

        return $this->render('category/category-list.html.twig', array(
            'categories' => $categories,
            'rayons' => $rayons
        ));
    }

    /**
     * Creates a new category entity.
     *
     * @Route("/categoryNew", name="category_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm('AppBundle\Form\CategoryType', $category);
        
        $form->handleRequest($request);
         $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted()){

            $existing = $em->getRepository('AppBundle:Category')->findOneBy(['catname' => $category->getCatname()]);
            if(!empty($existing)){
                if($existing->getId() != $category->getId()){
                    $form->get('catname')->addError(new formError('Ce libelle nest pas disponible'));
                }
            }
            $existing = $em->getRepository('AppBundle:Category')->findOneBy(['catdesc' => $category->getCatname()]);
            if(!empty($existing)){
                if($existing->getId() != $category->getId()){
                    $form->get('catdesc')->addError(new formError('Cette description nest pas disponible'));
                }
            }

            if($form->isValid()) {

                
                $em->persist($category);
                $em->flush();

                $icone = $request->files->get('icone');
                $category_main_path = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . '/photos/categories/';
                if ($icone != null) {
                    $filename = 'cat_'.$category->getId().'.'.$icone->guessExtension();
                   /* if (file_exists($this->container->getParameter('photo_category_directory').'/'. $filename)) {
                        unlink($this->container->getParameter('photo_category_directory').'/'. $filename);
                    }*/
                    if ($icone->move($this->container->getParameter('photo_category_directory'), $filename)) {
                        $iconePath = $category_main_path . $filename;
                        $category->setIcone($iconePath);
                        $em->merge($category);
                    }
                    else{
                        //die("bbbbb");
                    }
                }



                $em->flush();
                if(empty($category->getParent())){
                    $category->setParent($category);
                    $em->persist($category);
                    $em->flush();
                }

                return $this->redirectToRoute('category_list', array('id' => $category->getId()));
            }
        }

        return $this->render('category/category-new.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a category entity.
     *
     * @Route("/{id}/categoryShow", name="category_show")
     * @Method("GET")
     */
    public function showAction(Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);

        return $this->render('category/show.html.twig', array(
            'category' => $category,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing category entity.
     *
     * @Route("/{id}/categoryEdit", name="category_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('AppBundle:Category')->findOneById($id);

        $deleteForm = $this->createDeleteForm($category);
        $editForm = $this->createForm('AppBundle\Form\CategoryType', $category);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted()) {
            $iconePath = "PP";
            $existing = $em->getRepository('AppBundle:Category')->findOneBy(['catname' => $category->getCatname()]);
            if(!empty($existing)){
                if($existing->getId() != $category->getId()){
                    $editForm->get('catname')->addError(new formError('Ce libelle nest pas disponible'));
                }
            }



            if($editForm->isValid()) {
                $icone = $request->files->get('icone');
                $category_main_path = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . '/photos/categories/';
                if ($icone != null) {
                    $filename = 'cat_'.$category->getId().'.'.$icone->guessExtension();
                    if (file_exists($this->container->getParameter('photo_category_directory').'/'. $filename)) {
                        unlink($this->container->getParameter('photo_category_directory').'/'. $filename);
                    }
                    if ($icone->move($this->container->getParameter('photo_category_directory'), $filename)) {
                        $iconePath = $category_main_path . $filename;
                        $category->setIcone($iconePath);
                        $em->merge($category);
                    }
                    else{
                        //die("bbbbb");
                    }
                }
                //dump($icone);
                //dump($this->container->getParameter('photo_category_directory'));
                $this->getDoctrine()->getManager()->flush();
                //die("-----bbbbb");
                return $this->redirectToRoute('category_edit', array('id' => $category->getId()));
            }
        }

        return $this->render('category/category-edit.html.twig', array(
            'category' => $category,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a category entity.
     *
     * @Route("/{id}/categoryDelete", name="category_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->findOneById($id);

        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();

        return $this->redirectToRoute('category_list');
    }

    /**
     * Creates a form to delete a category entity.
     *
     * @param Category $category The category entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('category_delete', array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
