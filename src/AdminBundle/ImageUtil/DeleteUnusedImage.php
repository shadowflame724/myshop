<?php

namespace AdminBundle\ImageUtil;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DeleteUnusedImage extends Controller
{

    private $uploadImageRootDir;
    private static $count = null;

    private $doctrine;


    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }
    /**
     * @param mixed $uploadImageRootDir
     */
    public function setUploadImageRootDir($uploadImageRootDir)
    {
        $this->uploadImageRootDir = $uploadImageRootDir;
    }

    public function deleteImg()
    {
        $doctrine = $this->doctrine;
        $photoDirPath = $this->uploadImageRootDir;
        $iconDirPath = $this->uploadImageRootDir . "../icons/";
        $saleDirPath = $this->uploadImageRootDir . "../SalePhoto/";
        $fileNames = [];

        $photosNameList = $doctrine->getManager()
            ->createQuery("select p.fileName from DefaultBundle:ProductPhoto p ")
            ->getResult();
        $iconsNameList = $doctrine->getManager()
            ->createQuery("select p.iconFileName from DefaultBundle:Product p ")
            ->getResult();
        $saleNameList = $doctrine->getManager()
            ->createQuery("select p.salePhoto from DefaultBundle:SaleProduct p ")
            ->getResult();

        foreach ($photosNameList as $item) {
            $nameArr[] = $item["fileName"];
        }

        if ($handle = opendir($photoDirPath)) {
            while (false !== ($file = readdir($handle))) {
                if (!strstr($file, "_") AND ($file != '.') AND ($file != '..')) {
                    $fileNames[] = $file;
                }
            }
            closedir($handle);
            if (count($fileNames) > 0) {
                foreach ($fileNames as $photoFileName) {
                    if (in_array($photoFileName, $nameArr) == false) {

                        $smallPhotoName = "small_" . $photoFileName;
                        $photoFile = $photoDirPath . $photoFileName;
                        $smallPhotoFile = $photoDirPath . $smallPhotoName;

                        if (file_exists($smallPhotoFile)) {
                            unlink($smallPhotoFile);
                            self::$count++;
                        }
                        if (file_exists($photoFile)) {
                            unlink($photoFile);
                            self::$count++;
                        }
                    }
                }
            }
        }

        foreach ($iconsNameList as $item) {
        $nameArr[] = $item["iconFileName"];
    }

        if ($handle = opendir($iconDirPath)) {
            while (false !== ($file = readdir($handle))) {
                if (($file != '.') AND ($file != '..')) {
                    $fileNames[] = $file;
                }
            }
            closedir($handle);
            if ($fileNames) {
                foreach ($fileNames as $iconFileName) {
                    if (in_array($iconFileName, $nameArr) == false) {
                        $iconFile = $iconDirPath . $iconFileName;
                        if (file_exists($iconFile)) {
                            unlink($iconFile);
                            self::$count++;
                        }
                    }
                }
            }
        }

        foreach ($saleNameList as $item) {
            $nameArr[] = $item["salePhoto"];
        }

        if ($handle = opendir($saleDirPath)) {
            while (false !== ($file = readdir($handle))) {
                if (($file != '.') AND ($file != '..')) {
                    $fileNames[] = $file;
                }
            }
            closedir($handle);
            if ($fileNames) {
                foreach ($fileNames as $saleFileName) {
                    if (in_array($saleFileName, $nameArr) == false) {
                        $salePhotoFile = $saleDirPath . $saleFileName;
                        if (file_exists($salePhotoFile)) {
                            unlink($salePhotoFile);
                            self::$count++;
                        }
                    }
                }
            }
        }
        return self::$count;
    }
}
