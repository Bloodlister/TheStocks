<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ItemPromotion
 *
 * @ORM\Table(name="item_promotion")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ItemPromotionRepository")
 */
class ItemPromotion
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Item", inversedBy="discount")
     */
    private $item;

    /**
     * @var int
     *
     * @ORM\Column(name="discount", type="integer")
     *
     */
    private $discount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime")
     */
    private $endDate;


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
     * @param integer $item
     *
     * @return ItemPromotion
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get product
     *
     * @return int
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return ItemPromotion
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return ItemPromotion
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return int
     */
    public function getDiscount(): int
    {
        return $this->discount;
    }

    /**
     * @param int $discount
     */
    public function setDiscount(int $discount)
    {
        $this->discount = $discount;
    }
}

