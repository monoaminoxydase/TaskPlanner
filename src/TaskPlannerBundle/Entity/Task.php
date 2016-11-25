<?php

namespace TaskPlannerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="TaskPlannerBundle\Repository\TaskRepository")
 */
class Task
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
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="completed", type="boolean")
     */
    private $completed;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tasks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="TaskCategory", inversedBy="tasks")
     * @ORM\JoinTable(name="tasks_taskCategories")
     */
    private $taskCategories;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="task")
     */
    private $comments;

    public function __construct() {
        $this->taskCategories = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

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
     * @return Task
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
     * Set description
     *
     * @param string $description
     * @return Task
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set completed
     *
     * @param boolean $completed
     * @return Task
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * Get completed
     *
     * @return boolean 
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Set user
     *
     * @param \TaskPlannerBundle\Entity\User $user
     * @return Task
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

    /**
     * Add taskCategories
     *
     * @param \TaskPlannerBundle\Entity\TaskCategory $taskCategories
     * @return Task
     */
    public function addTaskCategory(\TaskPlannerBundle\Entity\TaskCategory $taskCategories)
    {
        $this->taskCategories[] = $taskCategories;

        return $this;
    }

    /**
     * Remove taskCategories
     *
     * @param \TaskPlannerBundle\Entity\TaskCategory $taskCategories
     */
    public function removeTaskCategory(\TaskPlannerBundle\Entity\TaskCategory $taskCategories)
    {
        $this->taskCategories->removeElement($taskCategories);
    }

    /**
     * Get taskCategories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTaskCategories()
    {
        return $this->taskCategories;
    }

    /**
     * Add comments
     *
     * @param \TaskPlannerBundle\Entity\Comment $comments
     * @return Task
     */
    public function addComment(\TaskPlannerBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \TaskPlannerBundle\Entity\Comment $comments
     */
    public function removeComment(\TaskPlannerBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    public function __toString()
    {
        return "$this->name";
    }
}
