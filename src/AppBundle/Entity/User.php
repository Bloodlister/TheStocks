<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface
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
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min="6", max="16")
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     *
     */
    private $password;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Role", inversedBy="users")
     * @ORM\JoinTable(name="users_roles",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     *     )
     */
    private $roles;

    /**
     * @var float
     *
     * @ORM\Column(name="cash", type="float", unique=false)
     * @Assert\NotNull()`
     */
    private $cash = 1000;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=50)
     */
    private $fullName = '';

    /**
     * @var int
     *
     * @ORM\Column(name="age", type="integer")
     * @Assert\GreaterThanOrEqual(value="13", message="You are below the allowed age.")
     * @Assert\LessThanOrEqual(value="100", message="You are above the allowed age.")
     */
    private $age = 13;

    /**
     * @var string
     *
     * @ORM\Column(name="bio", type="string", length=600)
     */
    private $bio = '';

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=20)
     */
    private $phoneNumber ='';

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=55)
     */
    private $country = '';

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address = '';

    /**
     * @var string
     *
     * @ORM\Column(name="post_code", type="string", length=16)
     */
    private $postCode = '';

    function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    /**
     * @return float
     */
    public function getCash()
    {
        return $this->cash;
    }

    /**
     * @param float $cash
     */
    public function setCash($cash)
    {
        $this->cash = $cash;
    }

    /**
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * @param string $bio
     */
    public function setBio($bio)
    {
        $this->bio = $bio;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
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
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
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

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return array_map(function (Role $role){return $role->getName();}, $this->roles->toArray());
    }

    public function addRole(Role $role)
    {
        $this->roles[] = $role;
        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getCartTotal(EntityManager $em)
    {
        $totalCost = 0;

        $repo = $em->getRepository('AppBundle:Cart');
        $carts = $repo->createQueryBuilder('c')
            ->where('c.user = :id')
            ->setParameter('id', $this->id)
            ->getQuery()
            ->getResult();

        foreach ($carts as $cart)
        {
            /** @var Cart $cart */

            $totalCost += $cart->getItem()->getPriceWithDiscount() * $cart->getQuantity();
        }

        return $totalCost;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * @param string $postCode
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    public function isEditor()
    {
        return in_array("ROLE_EDITOR", $this->getRoles());
    }

    public function isAdmin()
    {
        return in_array("ROLE_ADMIN", $this->getRoles());
    }
}

