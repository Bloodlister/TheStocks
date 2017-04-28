<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Purchase
 *
 * @ORM\Table(name="purchase")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PurchaseRepository")
 */
class Purchase
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
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Item", fetch="EAGER")
     */
    private $item;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", fetch="EAGER")
     */
    private $user;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var string
     * @ORM\Column(name="country", type="string", length=55)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="post_code", type="string", length=15)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $postCode;

    /**
     * @var string
     *
     * @ORM\Column(name="receiver", type="string", length=55)
     * @Assert\NotNull()
     */
    private $receiver;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string")
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $phoneNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="made_on", type="datetime")
     */
    private $madeOn = \DateTime::ATOM;

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
     * Set product
     *
     * @param integer $product
     *
     * @return Purchase
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return Purchase
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Purchase
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
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

    /**
     * @return \DateTime
     */
    public function getMadeOn()
    {
        return $this->madeOn;
    }

    /**
     * @param \DateTime $madeOn
     */
    public function setMadeOn($madeOn)
    {
        $this->madeOn = $madeOn;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
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
     * @return string
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param string $receiver
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * @param User $userInfo
     */
    public function setInfo(User $userInfo)
    {
        $this->setReceiver($userInfo->getFullName());
        $this->setPhoneNumber($userInfo->getPhoneNumber());
        $this->setAddress($userInfo->getAddress());
        $this->setCountry($userInfo->getCountry());
        $this->setPostCode($userInfo->getPostCode());
        $this->setMadeOn(new \DateTime('now'));
    }
}

