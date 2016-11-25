<?php

namespace TaskPlannerBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use TaskPlannerBundle\Entity\TaskCategory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Taskcategory controller.
 *
 * @Route("taskcategory")
 */
class TaskCategoryController extends Controller
{
    /**
     * Lists all taskCategory entities.
     *
     * @Route("/", name="taskcategory_index")
     * @Method("GET")
     */
    public function indexAction()
    {
            $em = $this->getDoctrine()->getManager();

            $taskCategories = $em->getRepository('TaskPlannerBundle:TaskCategory')->findByUser($this->getUser());//showing only categories defined by logged user

            return $this->render('TaskPlannerBundle:taskcategory:index.html.twig', array(
                'taskCategories' => $taskCategories,
            ));
    }

    /**
     * Creates a new taskCategory entity.
     *
     * @Route("/new", name="taskcategory_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $taskCategory = new Taskcategory();
        $form = $this->createForm('TaskPlannerBundle\Form\TaskCategoryType', $taskCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($taskCategory->getTasks() as $task) {
                if ($task->getCompleted() || $task->getUser()->getId() != $this->getUser()->getId()) {//user can select only tasks that he has previously defined. He can't choode other user's categories
                    $taskCategory->removeTask($task);//user can only select uncomplteted tasks
                }
            }

            $taskCategory->setUser($this->getUser());//prohibiting task category addition to other users' tasks

            $em = $this->getDoctrine()->getManager();
            $em->persist($taskCategory);
            $em->flush($taskCategory);

            return $this->redirectToRoute('taskcategory_show', array('id' => $taskCategory->getId()));
        }

        return $this->render('TaskPlannerBundle:taskcategory:new.html.twig', array(
            'taskCategory' => $taskCategory,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a taskCategory entity.
     *
     * @Route("/{id}", name="taskcategory_show")
     * @Method("GET")
     */
    public function showAction(TaskCategory $taskCategory)
    {
        if (!$this->getUser() || $this->getUser()->getId() != $taskCategory->getUser()->getId()) {
            return $this->render('TaskPlannerBundle::wronguser.html.twig');
        } else {

            $deleteForm = $this->createDeleteForm($taskCategory);

            return $this->render('TaskPlannerBundle:taskcategory:show.html.twig', array(
                'taskCategory' => $taskCategory,
                'delete_form' => $deleteForm->createView(),
            ));
        }
    }

    /**
     * Displays a form to edit an existing taskCategory entity.
     *
     * @Route("/{id}/edit", name="taskcategory_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TaskCategory $taskCategory)
    {
        if (!$this->getUser() || $this->getUser()->getId() != $taskCategory->getUser()->getId()) {
            return $this->render('TaskPlannerBundle::wronguser.html.twig');
        } else {
            $deleteForm = $this->createDeleteForm($taskCategory);
            $editForm = $this->createForm('TaskPlannerBundle\Form\TaskCategoryType', $taskCategory);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {

                foreach ($taskCategory->getTasks() as $task) {
                    if ($task->getCompleted()) {
                        $taskCategory->removeTask($task);
                    }
                }

                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('taskcategory_edit', array('id' => $taskCategory->getId()));
            }

            return $this->render('TaskPlannerBundle:taskcategory:edit.html.twig', array(
                'taskCategory' => $taskCategory,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }
    }

    /**
     * Deletes a taskCategory entity.
     *
     * @Route("/{id}", name="taskcategory_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TaskCategory $taskCategory)
    {
        if (!$this->getUser() || $this->getUser()->getId() != $taskCategory->getUser()->getId()) {
            return $this->render('TaskPlannerBundle::wronguser.html.twig');
        } else {
            $form = $this->createDeleteForm($taskCategory);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($taskCategory);
                $em->flush($taskCategory);
            }

            return $this->redirectToRoute('taskcategory_index');
        }
    }

    /**
     * Creates a form to delete a taskCategory entity.
     *
     * @param TaskCategory $taskCategory The taskCategory entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TaskCategory $taskCategory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('taskcategory_delete', array('id' => $taskCategory->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
