<?php

namespace TaskPlannerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * TaskCategory
 *
 * @ORM\Table(name="task_category")
 * @ORM\Entity(repositoryClass="TaskPlannerBundle\Repository\TaskCategoryRepository")
 */
class TaskCategory
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Task", mappedBy="taskCategories")
     */
    private $tasks;

    public function __construct() {
        $this->tasks = new ArrayCollection();
    }

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="taskCategories")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return TaskCategory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add tasks
     *
     * @param \TaskPlannerBundle\Entity\Task $tasks
     * @return TaskCategory
     */
    public function addTask(\TaskPlannerBundle\Entity\Task $tasks)
    {
        $this->tasks[] = $tasks;

        return $this;
    }

    /**
     * Remove tasks
     *
     * @param \TaskPlannerBundle\Entity\Task $tasks
     */
    public function removeTask(\TaskPlannerBundle\Entity\Task $tasks)
    {
        $this->tasks->removeElement($tasks);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    public function __toString()
    {
        return "$this->name";
    }

    /**
     * Set user
     *
     * @param \TaskPlannerBundle\Entity\User $user
     * @return TaskCategory
     */
    public function setUser(\TaskPlannerBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \TaskPlannerBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
