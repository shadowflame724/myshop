<?php

namespace AdminBundle\ImageUtil;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DeleteUnusedImage extends Controller
{

    private $webDir;
    /**
     * @var EntityManager
     */
    private $manager;
    private static $count = null;


    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param mixed $webDir
     */
    public function setWebDir($webDir)
    {
        $this->webDir = $webDir;
    }

    public function deleteImg()
    {
        $manager = $this->manager;
        $photoDirPath = $this->webDir . "photos/";
        $iconDirPath = $this->webDir . "icons/";
        $saleDirPath = $this->webDir . "SalePhoto/";
        $nameArr = [];

        $iconNameList = $this->manager
            ->createQuery("select i.iconFileName from DefaultBundle:Product i")
            ->getResult();
        $photoNamesList = $manager
            ->createQuery("select p.fileName from DefaultBundle:ProductPhoto p")
            ->getResult();
        $saleNamesList = $manager
            ->createQuery("select s.salePhoto from DefaultBundle:SaleProduct s")
            ->getResult();

        foreach ($photoNamesList as $item) {
            self::$count = $this->delete($photoDirPath, $item["fileName"]);
            $smallName = "small_" . $item["fileName"];
            self::$count = $this->delete($photoDirPath, $smallName);
        }

        foreach ($iconNameList as $item) {
            self::$count = $this->delete($iconDirPath, $item["iconFileName"]);
        }

        foreach ($saleNamesList as $item) {
            self::$count = $this->delete($saleDirPath, $item["salePhoto"]);
        }

        self::$count = $this->delete($saleDirPath, $nameArr);

        return self::$count;
    }

    private function delete($dir, $nameArr = null)
    {
        $fileNames = [];
        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                if (($file != '.') AND ($file != '..')) {
                    $fileNames[] = $file;
                }
            }
            closedir($handle);
            foreach ($fileNames as $fileName) {
                $fullFileName = $dir . $fileName;
                echo "<pre>";
                var_dump($fullFileName);
                echo "</pre>";
                if ($nameArr == null AND file_exists($fullFileName)) {
                    unlink($fullFileName);
                    self::$count++;
                    return self::$count;
                } elseif (in_array($fileName, $nameArr) == false AND file_exists($fullFileName)) {
                    unlink($fullFileName);
                    self::$count++;
                }
            }
        }
        return self::$count;
    }
}
