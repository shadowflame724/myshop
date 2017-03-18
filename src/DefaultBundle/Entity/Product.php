<?php

namespace DefaultBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="DefaultBundle\Repository\ProductRepository")
 */
class Product
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
     * @var string
     * @ORM\Column (name="iconFileName", type="string")
     *
     */
    private $iconFileName;

    /**
     * @return string
     */
    public function getIconFileName()
    {
        return $this->iconFileName;
    }

    /**
     * @param string $iconFileName
     */
    public function setIconFileName($iconFileName)
    {
        $this->iconFileName = $iconFileName;
    }

    /**
     * @var Manufacturer
     *
     * @ORM\ManyToOne(targetEntity="DefaultBundle\Entity\Manufacturer", inversedBy="productList")
     * @ORM\JoinColumn(name="id_manufacturer", referencedColumnName="id")
     */
    private $manufacturer;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="DefaultBundle\Entity\ProductPhoto", mappedBy="product")
     */
    private $photos;

    /**
     * @return ArrayCollection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @param ArrayCollection $photos
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;
    }

    /**
     * @return Manufacturer
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * @param Manufacturer $manufacturer
     */
    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;
    }

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="DefaultBundle\Entity\Category", inversedBy="productList")
     * @ORM\JoinColumn(name="id_category", referencedColumnName="id")
     */
    private $category;

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function __construct()
    {
        $date = new \DateTime("now");
        $this->setAddDate($date);
        $this->photos = new ArrayCollection();
    }

    /**
     * @var string
     *
     * @ORM\Column(name="Model", type="string", length=255)
     * @Assert\NotBlank(message = "field model is required")
     * @Assert\Length(
     *      min = 2,
     *      max = 254,
     *      minMessage = "Model name must be at least {{ limit }} characters long",
     *      maxMessage = "Model name cannot be longer than {{ limit }} characters"
     * )
     */
    private $model;

    /**
     * @var float
     *
     * @ORM\Column(name="Price", type="float")
     * @Assert\Type(
     * type="float",
     * message="The price must be numeric or double."
     * )
     */
    private $price;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="AddDate", type="datetime")
     */
    private $addDate;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="text")
     */
    private $description;

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
     * Set model
     *
     * @param string $model
     *
     * @return Product
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set addDate
     *
     * @param \DateTime $addDate
     *
     * @return Product
     */
    public function setAddDate($addDate)
    {
        $this->addDate = $addDate;

        return $this;
    }

    /**
     * Get addDate
     *
     * @return \DateTime
     */
    public function getAddDate()
    {
        return $this->addDate;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
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
     * Add photo
     *
     * @param \DefaultBundle\Entity\ProductPhoto $photo
     *
     * @return Product
     */
    public function addPhoto(\DefaultBundle\Entity\ProductPhoto $photo)
    {
        $this->photos[] = $photo;

        return $this;
    }

    /**
     * Remove photo
     *
     * @param \DefaultBundle\Entity\ProductPhoto $photo
     */
    public function removePhoto(\DefaultBundle\Entity\ProductPhoto $photo)
    {
        $this->photos->removeElement($photo);
    }
}
