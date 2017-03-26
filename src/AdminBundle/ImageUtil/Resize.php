<?php
/**
 * Created by PhpStorm.
 * User: UserPC
 * Date: 24.03.2017
 * Time: 14:28
 */

namespace AdminBundle\ImageUtil;


use AdminBundle\DTO\UploadImageResult;
use Eventviva\ImageResize;
use Intervention\Image\ImageManager;

class Resize
{
    private $supportImageSize;
    private $webDir;

    /**
     * @param string $webDir
     */
    public function setUploadImageRootDir($webDir)
    {
        $this->webDir = $webDir;
    }

    /**
     * Resize constructor.
     * @param $supportImageSize
     */
    public function __construct($supportImageSize)
    {
        $this->supportImageSize = $supportImageSize;
    }

    public function resize($photoFile)
    {
        $photoDirPath = $webDir . "photos/";
        $img = new ImageResize($photoFile);
        $height = $this->supportImageSize[0][0];
        $weight = $this->supportImageSize[0][1];
        $img->resizeToBestFit($height, $weight);
        $smallPhotoName = "small_" . $photoFileName;
        $img->save($photoDirPath . $smallPhotoName);
        $result = new UploadImageResult($photoFileName, $smallPhotoName);
    }
}