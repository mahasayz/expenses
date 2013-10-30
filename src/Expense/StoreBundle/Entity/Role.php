<?php

namespace Expense\StoreBundle\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * @Entity
 * @author hossain.nowshad
 *
 */
class Role implements RoleInterface{
	/**
	 * @Id
	 * @GeneratedValue(strategy="AUTO")
	 * @Column(name="id", type="integer")
	 */
	private $id;
	
	/**
	 * @Column(name="name", type="string", length=30)
	 */
	private $name;
	
	/**
	 * @Column(name="role", type="string", length=20, unique=true)
	 */
	private $role;
	
	
	
	
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
     * @return Role
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
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }
}