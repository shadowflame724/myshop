<?php

namespace DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="DefaultBundle\Repository\CategoryRepository")
 */
class Category
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
     *
     * @ORM\Column(name="Name", type="string", length=255)
     * @Assert\NotBlank(message="Field CategoryName is required")
     * @Assert\Length(
     *     min="2",
     *     max="254",
     *     minMessage="Too short name of category. Min {{ limit }} symbol",
     *     maxMessage="Too lengthy name of category. Max {{ limit }} symbol")
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DefaultBundle\Entity\Product", mappedBy="category")
     */
    private $productList;
    
    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="DefaultBundle\Entity\Category", inversedBy="childrenCategories")
     * @ORM\JoinColumn(name="id_parent", referencedColumnName="id")
     */
    private $parentCategory;
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DefaultBundle\Entity\Category", mappedBy="parentCategory")
     */
    private $childrenCategories;


    public function __construct()
    {
        $this->productList = new ArrayCollection();
    }

    public function addProduct(Product $product)
    {
        $product->setCategory($this);
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
     * Set name
     *
     * @param string $name
     *
     * @return Category
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
     * Add productList
     *
     * @param \DefaultBundle\Entity\Product $productList
     *
     * @return Category
     */
    public function addProductList(\DefaultBundle\Entity\Product $productList)
    {
        $this->productList[] = $productList;

        return $this;
    }

    /**
     * Remove productList
     *
     * @param \DefaultBundle\Entity\Product $productList
     */
    public function removeProductList(\DefaultBundle\Entity\Product $productList)
    {
        $this->productList->removeElement($productList);
    }

    /**
     * Set parentCategory
     *
     * @param \DefaultBundle\Entity\Category $parentCategory
     *
     * @return Category
     */
    public function setParentCategory( $parentCategory = null)
    {
        $this->parentCategory = $parentCategory;

        return $this;
    }

    /**
     * Get parentCategory
     *
     * @return \DefaultBundle\Entity\Category
     */
    public function getParentCategory()
    {
        return $this->parentCategory;
    }

    /**
     * Add childrenCategory
     *
     * @param \DefaultBundle\Entity\Category $childrenCategory
     *
     * @return Category
     */
    public function addChildrenCategory(Category $childrenCategory)
    {
        $this->childrenCategories[] = $childrenCategory;

        return $this;
    }

    /**
     * Remove childrenCategory
     *
     * @param \DefaultBundle\Entity\Category $childrenCategory
     */
    public function removeChildrenCategory(Category $childrenCategory)
    {
        $this->childrenCategories->removeElement($childrenCategory);
    }

    /**
     * Get childrenCategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildrenCategories()
    {
        return $this->childrenCategories;
    }
}
