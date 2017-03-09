<?php

namespace AdminBundle\ImageUtil;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DeleteUnusedImage extends Controller
{

    private $uploadImageRootDir;
    private static $count = null;

    /**
     * @param mixed $uploadImageRootDir
     */
    public function setUploadImageRootDir($uploadImageRootDir)
    {
        $this->uploadImageRootDir = $uploadImageRootDir;
    }

    public function deleteImg($photosNameList, $iconsNameList)
    {
        $photoDirPath = $this->uploadImageRootDir;
        $iconDirPath = $this->uploadImageRootDir . "../icons/";
        $fileNames = [];

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
        return self::$count;
    }
}
