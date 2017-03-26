<?php

namespace DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Manufacturer
 *
 * @ORM\Table(name="manufacturer")
 * @ORM\Entity(repositoryClass="DefaultBundle\Repository\ManufacturerRepository")
 */
class Manufacturer
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
        return strval( $this->getId() );
    }
    /**
     * @var string
     *
     * @ORM\Column(name="Company", type="string", length=255)
     * @Assert\NotBlank(message = "field company name is required")
     * @Assert\Length(
     *      min = 2,
     *      max = 254,
     *      minMessage = "name must be at least {{ limit }} characters long",
     *      maxMessage = "name cannot be longer than {{ limit }} characters"
     * )
     */
    private $company;

    /**
     * @var string
     *
     * @ORM\Column(name="Country", type="string", length=255)
     * @Assert\NotBlank(message = "field county is required")
     * @Assert\Length(
     *      min = 2,
     *      max = 254,
     *      minMessage = "county must be at least {{ limit }} characters long",
     *      maxMessage = "county cannot be longer than {{ limit }} characters"
     * )
     */
    private $country;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DefaultBundle\Entity\Product", mappedBy="manufacturer")
     */
    private $productList;

    public function __construct()
    {
        $this->productList = new ArrayCollection();
    }

    public function addProduct(Product $product)
    {
        $product->setManufacturer($this);
        $this->productList[] = $product;
    }

    /**
     * @return ArrayCollection
     */
    public function getProductList()
    {
        return $this->productList;
    }

    /**
     * @param mixed Product
     */
    public function setProductList(Product $productList)
    {
        $this->productList = $productList;
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
     * Set company
     *
     * @param string $company
     *
     * @return Manufacturer
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Manufacturer
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }
}

