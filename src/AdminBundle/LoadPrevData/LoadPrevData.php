<?php

namespace AdminBundle\LoadPrevData;

use AdminBundle\Entity\User;
use DefaultBundle\Entity\Category;
use DefaultBundle\Entity\Manufacturer;
use DefaultBundle\Entity\Product;
use DefaultBundle\Entity\ProductPhoto;
use DefaultBundle\Entity\SaleProduct;
use Eventviva\ImageResize;
use Intervention\Image\ImageManager;

class LoadPrevData
{
    private $manager;
    private $kernel;
    private $encoder;

    public function __construct($manager, $kernel, $encoder)
    {
        $this->manager = $manager;
        $this->kernel = $kernel;
        $this->encoder = $encoder;
    }

    public function loadUser()
    {
        $manager = $this->manager;
        $encoder = $this->encoder;
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setUsername("Admin" . $i);
            $hashPas = $encoder->encodePassword($user, "1234");
            $user->setPassword($hashPas);
            $user->setEmail("test" . rand(10000, 99999) . "@gmail.com");
            $manager->persist($user);
            $manager->flush();
        }
        return true;
    }

    public function loadManufacturer()
    {
        $manager = $this->manager;
        $companyNames = ["Apple Inc.", "HTC", "TDK", "Nokia", "Hewlett-Packard",
            "Sony", "Samsung", "Intel", "IBM", "Pioneer Corporation"];
        foreach ($companyNames as $companyName) {
            $manufacturer = new Manufacturer();
            $manufacturer->setCompany($companyName);
            $manufacturer->setCountry(rand(100, 999));
            $manager->persist($manufacturer);
            $manager->flush();
        }
        return true;
    }

    public function loadCategory()
    {
        $manager = $this->manager;
        for ($i = 1; $i <= 5; $i++) {
            $category = new Category();
            $category->setName("Root" . $i);
            $manager->persist($category);
            $manager->flush();
        }
        for ($i = 1; $i <= 5; $i++) {
            $parentCategory = $manager->getRepository("DefaultBundle:Category")->find($i);
            $category = new Category();
            $category->setName("Child_1_lvl" . $i);
            $category->setParentCategory($parentCategory);
            $manager->persist($category);
            $manager->flush();
        }
        for ($i = 6; $i <= 10; $i++) {
            $parentCategory = $manager->getRepository("DefaultBundle:Category")->find($i);
            $category = new Category();
            $category->setName("Child_2_lvl" . $i);
            $category->setParentCategory($parentCategory);
            $manager->persist($category);
            $manager->flush();
        }
        return true;
    }

    private function recurse_copy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    public function loadProduct()
    {
        $manager = $this->manager;

        $src = $this->kernel->getRootDir() . "/../source/icons/";
        $dst = $this->kernel->getRootDir() . "/../web/icons/";
        $dir = opendir($src);
        @mkdir($dst);

        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $newIconFileName = rand(1000, 9999) . ".jpg";
                copy($src . $file, $dst . $newIconFileName);
                $product = new Product();
                $product->setModel("Model" . rand(100, 999));
                $product->setPrice(rand(100, 999));

                $category = $manager->getRepository("DefaultBundle:Category")->find(rand(1, 10));
                $product->setCategory($category);

                $manufacturer = $manager->getRepository("DefaultBundle:Manufacturer")->find(rand(1, 10));
                $product->setManufacturer($manufacturer);

                $product->setIconFileName($newIconFileName);
                $product->setDescription("Some description for some product");

                $manager->persist($product);
                $manager->flush();
            }
        }
        return true;
    }

    public function loadPhoto()
    {
        $manager = $this->manager;

        $src = $this->kernel->getRootDir() . "/../source/photos/";
        $dst = $this->kernel->getRootDir() . "/../web/photos/";

        $productList = $manager->getRepository("DefaultBundle:Product")->findAll();

        foreach ($productList as $product) {
            $dir = opendir($src);
            @mkdir($dst);
            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {
                    $newPhotoName = rand(1000, 9999) . ".jpg";
                    copy($src . $file, $dst . $newPhotoName);
                    $photo = new ProductPhoto();
                    $photo->setTitle(rand(1000, 9999));
                    $photo->setFileName($newPhotoName);
                    $img = new ImageResize($dst . $newPhotoName);
                    $img->resizeToBestFit(250, 250);
                    $img->save($dst . "small_" . $newPhotoName);
                    $photo->setSmallFileName("small_" . $newPhotoName);
                    $photo->setProduct($product);

                    $manager->persist($photo);
                    $manager->flush();
                }
            }
            closedir($dir);
        }
        return true;
    }

    public function loadSaleProduct()
    {
        $manager = $this->manager;
        $kernel = $this->kernel;

        $dst = $kernel->getRootDir() . "/../web/SalePhoto/";
        @mkdir($dst);
        $image = new ImageManager(array('driver' => 'gd'));

        $productList = $manager->getRepository("DefaultBundle:Product")->findAll();
        $randProduct = array_rand($productList, 2);

        var_dump($randProduct);
        foreach ($randProduct as $product) {
            $saleProduct = new SaleProduct();

            $saleProduct->setProduct($productList[$product]);

            $iconFileName = $kernel->getRootDir() . "/../web/icons/" . $productList[$product]->getIconFileName();
            $saleStamp = $kernel->getRootDir() . "/../source/SalePhoto/SalePhoto.png";

            $image = new ImageManager(array('driver' => 'gd'));

            $image->make($iconFileName)->resize(200, 130)->insert($saleStamp)
                ->save($dst . $productList[$product]->getIconFileName());

            $saleProduct->setSalePhoto($productList[$product]->getIconFileName());
            $saleProduct->setSalePrice(rand(00,99));

            $date = new \DateTime("now");

            $saleProduct->setSaleDate($date);
            $saleProduct->setSaleDescription(rand("1234567890", "1234567890"));

            $manager->persist($saleProduct);
            $manager->flush();
        }
        return true;
    }

}
