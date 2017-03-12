<?php

namespace DefaultBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SaleProduct
 *
 * @ORM\Table(name="sale_product")
 * @ORM\Entity(repositoryClass="DefaultBundle\Repository\SaleProductRepository")
 */
class SaleProduct
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    public function __toString()
    {
        return strval($this->getId());
    }

    /**
     * @var float
     *
     * @ORM\Column(name="sale_price", type="float")
     */
    private $salePrice;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sale_date", type="datetime")
     */
    private $saleDate;

    /**
     * @var string
     *
     * @ORM\Column(name="sale_description", type="text")
     * @Assert\NotBlank(message = "field sale description is required")
     * @Assert\Length(
     *      min = 10,
     *      max = 254,
     *      minMessage = "sale_description must be at least {{ limit }} characters long",
     *      maxMessage = "sale_description cannot be longer than {{ limit }} characters"
     * )
     */
    private $saleDescription;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToOne(targetEntity="DefaultBundle\Entity\Product")
     * @ORM\JoinColumn(name="id_product", referencedColumnName="id")
     */
    private $product;

    /**
     * @var string
     *
     * @ORM\Column(name="salePhoto", type="string")
     */
    private $salePhoto;

    /**
     * @return string
     */
    public function getSalePhoto()
    {
        return $this->salePhoto;
    }

    /**
     * @param string $salePhoto
     */
    public function setSalePhoto($salePhoto)
    {
        $this->salePhoto = $salePhoto;
    }

    /**
     * @return ArrayCollection
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param ArrayCollection $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
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
     * Set salePrice
     *
     * @param float $salePrice
     *
     * @return SaleProduct
     */
    public function setSalePrice($salePrice)
    {
        $this->salePrice = $salePrice;

        return $this;
    }

    /**
     * Get salePrice
     *
     * @return float
     */
    public function getSalePrice()
    {
        return $this->salePrice;
    }

    /**
     * Set saleDate
     *
     * @param \DateTime $saleDate
     *
     * @return SaleProduct
     */
    public function setSaleDate($saleDate)
    {
        $this->saleDate = $saleDate;
        
        return $this;
    }

    /**
     * Get saleDate
     *
     * @return \DateTime
     */
    public function getSaleDate()
    {
        return $this->saleDate;
    }

    /**
     * Set saleDescription
     *
     * @param string $saleDescription
     *
     * @return SaleProduct
     */
    public function setSaleDescription($saleDescription)
    {
        $this->saleDescription = $saleDescription;

        return $this;
    }

    /**
     * Get saleDescription
     *
     * @return string
     */
    public function getSaleDescription()
    {
        return $this->saleDescription;
    }
}

