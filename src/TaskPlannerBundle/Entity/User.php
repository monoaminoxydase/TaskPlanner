<?php

namespace TaskPlannerBundle\Entity;

    use FOS\UserBundle\Model\User as BaseUser;
    use Doctrine\ORM\Mapping as ORM;
    use Doctrine\Common\Collections\ArrayCollection;

    /**
    * @ORM\Entity
    * @ORM\Table(name="fos_user")
    */
    class User extends BaseUser
    {
        /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
        protected $id;

        /**
         * @ORM\OneToMany(targetEntity="Task", mappedBy="user")
         */
        private $tasks;

        /**
         * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
         */
        private $comments;

        /**
         * @ORM\OneToMany(targetEntity="TaskCategory", mappedBy="user")
         */
        private $taskCategories;

        public function __construct()
        {
            parent::__construct();
            $this->tasks = new ArrayCollection();
            $this->comments = new ArrayCollection();
            $this->taskCategories = new ArrayCollection();
        }
    
    /**
     * Add tasks
     *
     * @param \TaskPlannerBundle\Entity\Task $tasks
     * @return User
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

    /**
     * Add comments
     *
     * @param \TaskPlannerBundle\Entity\Comment $comments
     * @return User
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

    /**
     * Add taskCategories
     *
     * @param \TaskPlannerBundle\Entity\TaskCategory $taskCategories
     * @return User
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
}
