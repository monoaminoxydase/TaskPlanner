<?php

namespace TaskPlannerBundle\Controller;

use TaskPlannerBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * User controller.
 *
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @Route("/", name="hello")
     */
    public function helloAction()
    {
        return $this->render('TaskPlannerBundle::base.html.twig');
    }

}
