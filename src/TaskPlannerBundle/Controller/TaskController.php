<?php

namespace TaskPlannerBundle\Controller;

use TaskPlannerBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Task controller.
 *
 * @Route("task")
 */
class TaskController extends Controller
{
    /**
     * Lists all task entities.
     *
     * @Route("/", name="task_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tasks = $em->getRepository('TaskPlannerBundle:Task')->findByUser($this->getUser()->getId());

        return $this->render('TaskPlannerBundle:task:index.html.twig', array(
            'tasks' => $tasks,
        ));
    }

    /**
     * Creates a new task entity.
     *
     * @Route("/new", name="task_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $task = new Task();
        $form = $this->createForm('TaskPlannerBundle\Form\TaskType', $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush($task);

            return $this->redirectToRoute('task_show', array('id' => $task->getId()));
        }

        return $this->render('TaskPlannerBundle:task:new.html.twig', array(
            'task' => $task,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a task entity.
     *
     * @Route("/{id}", name="task_show")
     * @Method("GET")
     */
    public function showAction(Task $task)
    {
        if (!$this->getUser() || $this->getUser()->getId() != $task->getUser()->getId()) {
            return $this->render('TaskPlannerBundle::wronguser.html.twig');
        } else {
            $deleteForm = $this->createDeleteForm($task);

            return $this->render('TaskPlannerBundle:task:show.html.twig', array(
                'task' => $task,
                'delete_form' => $deleteForm->createView(),
            ));
        }
    }

    /**
     * Displays a form to edit an existing task entity.
     *
     * @Route("/{id}/edit", name="task_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Task $task)
    {
        if (!$this->getUser() || $this->getUser()->getId() != $task->getUser()->getId() || $task->getCompleted()) {
            return $this->render('TaskPlannerBundle::wronguser.html.twig');
        } else {

        $editForm = $this->createForm('TaskPlannerBundle\Form\TaskType', $task);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('task_edit', array('id' => $task->getId()));
        }

        return $this->render('TaskPlannerBundle:task:edit.html.twig', array(
            'task' => $task,
            'edit_form' => $editForm->createView(),
        ));
    }
    }

    /**
     * Deletes a task entity.
     *
     * @Route("/{id}", name="task_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Task $task)
    {
        if (!$this->getUser() || $this->getUser()->getId() != $task->getUser()->getId() || $task->getCompleted()) {
            return $this->render('TaskPlannerBundle::wronguser.html.twig');
        } else {

        $form = $this->createDeleteForm($task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $comments = $em->getRepository('TaskPlannerBundle:Comment')->findByTask($task->getId());

            for ($i = 0; $i<count($comments); $i++) {
                $em->remove($comments[$i]);
            }

            $em->remove($task);
            $em->flush($task);
        }

        return $this->redirectToRoute('task_index');
    }
    }

    /**
     * Creates a form to delete a task entity.
     *
     * @param Task $task The task entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Task $task)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('task_delete', array('id' => $task->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
