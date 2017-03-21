<?php
/**
 * Created by PhpStorm.
 * User: god
 * Date: 18.03.17
 * Time: 10:54
 */

namespace AdminBundle\Util;

use DefaultBundle\Entity\Category;
use DefaultBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class ImportExport
{
    private $manager;
    private $kernel;

    /**
     * EntityManager
     * @param $manager
     */
    public function __construct(EntityManagerInterface $manager, $kernel)
    {
        $this->manager = $manager;
        $this->kernel = $kernel;
    }

    public function export()
    {
        $manager = $this->manager;
        $kernel = $this->kernel;
        $csvDir = $kernel->getRootDir() ."/../source/CSV";

        $url = "http://" . $_SERVER["SERVER_NAME"] . ":" .  $_SERVER['SERVER_PORT'];

        @mkdir($csvDir);
        $date = date_format(new \DateTime("now"), "Y-m-d");
        $csvFullName = $csvDir . "/dump_" . "$date" . ".csv";
        $products = $manager->getRepository("DefaultBundle:Product")->findAll();
        $csv = "Model,Price,AddDate,Description,Category,Manufacturer,iconFileName" . "\n";

        foreach ($products as $product){
            $csv .= $product->getModel() . "," .
                $product->getPrice() . "," .
                date_format($product->getAddDate(), "Y-m-d H:i:s") . "," .
                $product->getDescription() . "," .
                $url .  "/icons/" . $product->getIconFileName() . "," . "\n";
        }
        file_put_contents($csvFullName, $csv);
        return true;
    }

    public function import($filePath, $flag = null)
    {
        $manager = $this->manager;
        $kernel = $this->kernel;
        $scrIconDir = $kernel->getRootDir() ."/../web/icons_2/";
        @mkdir($scrIconDir);

        $fh = fopen($filePath, "r");
        if ($fh == null) {
            throw new \Exception("Can't open file!");
        }
        if($flag != null){
            $manager->getConnection()->exec("SET foreign_key_checks = 0");
            $manager->getConnection()->exec("TRUNCATE TABLE product");
        }

        fgetcsv($fh);
        while ( ($data = fgetcsv($fh)) != FALSE )
        {
            $iconName = rand(1000, 9999);
            $exArr = explode(".", $data[4]);
            $extension = end($exArr);
            if ($data[0] !== "" and $data[1] !== "" and $data[2] !== "" and $data[3] !== ""
                and $data[4] !== "" and ($extension == "png" || "jpg" || "jpeg" || "bmp" || "gif")) {
                $product = new Product();
                $product->setModel($data[0]);
                $product->setPrice($data[1]);
                $product->setAddDate(new \DateTime($data[2]));
                $product->setDescription($data[3]);
                $product->setIconFileName($data[4]);
                try {copy($data[4], $scrIconDir .  $iconName . "." . $extension);}
                catch (\Exception $ex){
                    throw $ex;
                }
                $this->manager->persist($product);
                $this->manager->flush();
            } else return false;
        }
        fclose($fh);
        return true;
    }

}
