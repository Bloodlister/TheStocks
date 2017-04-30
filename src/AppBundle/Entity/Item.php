<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Item
 *
 * @ORM\Table(name="item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ItemRepository")
 */
class Item
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", fetch="EAGER")
     */
    private $user;

    /**
     * @ORM\Column(name="image_path", type="string", length=255, unique=false)
     * @Assert\Image()
     */
    private $imagePath = 'images/items/default.jpg';

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\Length(
     *     min="6",
     *     minMessage="The title is below the minimum amount of character",
     *     max="200",
     *     maxMessage="The title is over the maximum amount of character"
     * )
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000)
     * @Assert\Length(
     *     min="50",
     *     minMessage="The title is below the minimum amount of character",
     *     max="1000",
     *     maxMessage="The title is over the maximum amount of character"
     * )
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category")
     */
    private $category;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_live", type="boolean")
     */
    private $isLive = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt = \DateTime::ATOM;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt = null;


    /**
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\ItemPromotion", mappedBy="item", fetch="EAGER")
     */
    private $itemDiscount;


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
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param  $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * @param mixed $imagePath
     */
    public function setImagePath($imagePath)
    {

        $this->imagePath = $imagePath;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Item
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
     *
     * @return Item
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
     * Set category
     *
     * @param integer $category
     *
     * @return Item
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return int
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Item
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Item
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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTime $deletedAt
     */
    public function setDeletedAt(\DateTime $deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @return bool
     */
    public function isLive(): bool
    {
        return $this->isLive;
    }

    /**
     * @param bool $isLive
     */
    public function setIsLive(bool $isLive)
    {
        $this->isLive = $isLive;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return ItemPromotion
     */
    public function getItemDiscount()
    {
        return $this->itemDiscount;
    }

    /**
     * @param mixed $itemDiscount
     */
    public function setItemDiscount($itemDiscount)
    {
        $this->itemDiscount = $itemDiscount;
    }

    public function getPriceWithDiscount()
    {
        if ($this->itemDiscount == null)
        {
            return $this->price;
        }
        else
        {
            $now = new \DateTime('now');
            if ($now >= $this->getItemDiscount()->getStartDate() && $now <= $this->getItemDiscount()->getEndDate())
                return $this->price - $this->price * ($this->getItemDiscount()->getDiscount() / 100);
            else
                return $this->price;
        }
    }
}

