<?php

namespace DefaultBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProductPhoto
 *
 * @ORM\Table(name="product_photo")
 * @ORM\Entity(repositoryClass="DefaultBundle\Repository\ProductPhotoRepository")
 */
class ProductPhoto
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
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message = "field title is required")
     * @Assert\Length(
     *      min = 2,
     *      max = 254,
     *      minMessage = "title must be at least {{ limit }} characters long",
     *      maxMessage = "title cannot be longer than {{ limit }} characters"
     * )
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="fileName", type="string", length=255, unique=false)
     */
    private $fileName;

    /**
     * @var string
     *
     * @ORM\Column(name="smallFileName", type="string", length=255)
     */
    private $smallFileName;

    /**
     * @return string
     */
    public function getSmallFileName()
    {
        return $this->smallFileName;
    }

    /**
     * @param string $smallFileName
     */
    public function setSmallFileName($smallFileName)
    {
        $this->smallFileName = $smallFileName;
    }

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToOne(targetEntity="DefaultBundle\Entity\Product", inversedBy="photos")
     * @ORM\JoinColumn(name="id_product", referencedColumnName="id")
     */
    private $product;

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
     * Set title
     *
     * @param string $title
     *
     * @return ProductPhoto
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     *
     * @return ProductPhoto
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set product
     *
     * @param \DefaultBundle\Entity\Product $product
     *
     * @return ProductPhoto
     */
    public function setProduct(\DefaultBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \DefaultBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
