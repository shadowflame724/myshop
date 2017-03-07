<?php

namespace AdminBundle\ImageUtil;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DeleteUnusedImage extends Controller
{
    /**
     * @var ImageDelete
     */
    private $imageDelete;
    private $uploadImageRootDir;
    private static $count = null;

    public function __construct($imageDelete)
    {
        $this->imageDelete = $imageDelete;
    }

    /**
     * @param mixed $uploadImageRootDir
     */
    public function setUploadImageRootDir($uploadImageRootDir)
    {
        $this->uploadImageRootDir = $uploadImageRootDir;
    }

    public function deleteImg($dbNameList)
    {
        $photoDirPath = $this->uploadImageRootDir;
        $imageDelete = $this->imageDelete;

        foreach ($dbNameList as $item) {
            $nameArr[] = $item["fileName"];
        }

        if ($handle = opendir($photoDirPath)) {
            while (false !== ($file = readdir($handle))) {
                if (!strstr($file, "_") AND strlen($file > 2)) {
                    $fileNames[] = $file;
                }
            }
            closedir($handle);
            foreach ($fileNames as $photoFileName) {
                if (in_array($photoFileName, $nameArr) == false) {
                    self::$count+=2;
                    $imageDelete->imageDelete($photoFileName);
                }
            }
        }
        return self::$count;
    }
}
