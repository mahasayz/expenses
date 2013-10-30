<?php

namespace Expense\StoreBundle\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Entity(repositoryClass="ExpenseRepository")
 */
class Expense {
	/**
	 *	@Id	@GeneratedValue
	 * @Column(type="integer")
	 */
	private $id;
	
	/**
	 * @Assert\Regex(pattern="/\d+/")
	 * @Column(type="decimal", scale=2)
	 */
	private $amount;
	/**
	 *	@Column(type="string", nullable=true)
	 */
	private $description;
	
	/**
	 *	@ManyToOne(targetEntity="User", inversedBy="id")
	 */
	private $user;
	
	/**
	 * @Column(type="datetime")
	 * @var unknown
	 */
	private $created;
	

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
     * Set amount
     *
     * @param float $amount
     * @return Expense
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return float 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Expense
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
     * Set user
     *
     * @param \Expense\StoreBundle\Entity\User $user
     * @return Expense
     */
    public function setUser(\Expense\StoreBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Expense\StoreBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Expense
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }
}