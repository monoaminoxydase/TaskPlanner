<?php

namespace TaskPlannerBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use TaskPlannerBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Comment controller.
 *
 * @Route("comment")
 */
class CommentController extends Controller
{
    /**
     * Lists all comment entities.
     *
     * @Route("/{taskId}", name="comment_index")
     * @Method("GET")
     */
    public function indexAction($taskId)
    {
        $task = $this->getDoctrine()->getRepository('TaskPlannerBundle:Task')->find($taskId);

        if (!$this->getUser() || $this->getUser()->getId() != $task->getUser()->getId()) {
            return $this->render('TaskPlannerBundle::wronguser.html.twig');
        } else {
            return $this->render('TaskPlannerBundle:comment:index.html.twig', array(
                'task' => $task,
            ));
        }
    }

    /**
     * Creates a new comment entity.
     *
     * @Route("/new/{taskId}", name="comment_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $taskId)
    {
        $task = $this->getDoctrine()->getRepository('TaskPlannerBundle:Task')->find($taskId);
        if ($task && !$task->getCompleted() && $this->getUser()
            && $this->getUser()->getId() === $task->getUser()->getId()) {
            $comment = new Comment();

            $comment->setTask($task);
            $task->addComment($comment);

            $form = $this->createForm('TaskPlannerBundle\Form\CommentType', $comment);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush($comment);

                return $this->redirectToRoute('task_show', array('id' => $taskId));
            }

            return $this->render('TaskPlannerBundle:comment:new.html.twig', array(
                'comment' => $comment,
                'task' => $task,
                'form' => $form->createView(),
            ));
        } else {
            return new Response("<html><head></head><body><p>Cannot complete action.</p></body></html>");
        }
    }


    /**
     * Displays a form to edit an existing comment entity.
     *
     * @Route("/edit/{id}/{taskId}", name="comment_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Comment $comment, $taskId)
    {
        $task = $this->getDoctrine()->getRepository('TaskPlannerBundle:Task')->find($taskId);
        if ($task && !$task->getCompleted() && $this->getUser()
            && $this->getUser()->getId() === $task->getUser()->getId())
        {
            $editForm = $this->createForm('TaskPlannerBundle\Form\CommentType', $comment);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('task_show', array('id' => $taskId));
            }

            return $this->render('TaskPlannerBundle:comment:edit.html.twig', array(
                'comment' => $comment,
                'task' => $task,
                'edit_form' => $editForm->createView(),
            ));
        } else {
            return new Response("<html><head></head><body><p>Cannot complete action.</p></body></html>");
        }
    }

    /**
     * @Route("/deleteComment/{id}/{taskId}", name="comment_delete")
     *
     */

    public function deleteAction($id, $taskId)
    {
        $task = $this->getDoctrine()->getRepository('TaskPlannerBundle:Task')->find($taskId);
        if ($task && !$task->getCompleted()&& $this->getUser()
            && $this->getUser()->getId() === $task->getUser()->getId()) {
            $comment = $this->getDoctrine()->getRepository('TaskPlannerBundle:Comment')->find($id);

            if ($comment) {

                    $em = $this->getDoctrine()->getManager();
                    $em->remove($comment);
                    $em->flush();

                return $this->redirectToRoute('task_show', array('id' => $taskId));

            } else {
                return new Response("<html><head></head><body><p>Comment does not exist!</p></body></html>");
            }
        } else {
            return new Response("<html><head></head><body><p>Cannot complete action.</p></body></html>");
        }
    }
}

