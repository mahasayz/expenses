<?php

namespace Expense\StoreBundle\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Symfony\Component\Security\Core\User\UserInterface;
use Expense\StoreBundle\Utils;

/**
 * @Entity
 */
class User implements UserInterface, \Serializable{
	
	/**
	 * @Id @GeneratedValue @Column(type="integer")
	 * @var int
	 */
	protected $id;
	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $firstName;
	
	/**
	 * @Column(type="string", nullable=true)
	 * @var string
	 */
	protected $lastName;
	
	/**
	 * @Column(type="string")
	 */
	protected $username;
	
	/**
	 * @Column(type="string")
	 */
	protected $password;
	
	/**
	 * @Column(type="string")
	 */
	protected $salt;
	
	/**
	 * @Column(type="string")
	 */
	protected $countryCode;
	
    /**
     * @ManyToMany(targetEntity="Role")
     * @var unknown
     */
    protected $roles;

	
	public function __construct(){
		$this->roles = new ArrayCollection();
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }



    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
    	if($this->salt == null)
    		$this->setSalt( "df". (date("Ymd")) /2);
    	return $this->salt;
    }
    
    public function eraseCredentials(){
    }

    /**
     * Add roles
     *
     * @param \Expense\StoreBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\Expense\StoreBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;
    
        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Expense\StoreBundle\Entity\Role $roles
     */
    public function removeRole(\Expense\StoreBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }
    
    public function serialize()
    {
    	return serialize(array(
    			$this->id,
    	));
    }
    
    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
    	list (
    			$this->id,
    	) = unserialize($serialized);
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

   

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    public function getCurrencySymbol()
    {
    	return Utils::getCountry()[$this->countryCode];
    }
    
    /**
     * Set countryCode
     *
     * @param string $countryCode
     * @return User
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    
        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string 
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }
}