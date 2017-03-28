<?php

namespace AdminBundle\ImageUtil;

use AdminBundle\Controller\ProductController;
use DefaultBundle\Entity\Product;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AdminBundle\DTO\UploadImageResult;
use Eventviva\ImageResize;

class UploadImageService
{
    /**
     * @var CheckImg
     */
    private $checkImg;
    /**
     * @var Resize
     */
    private $resize;

    private $webDir;
    private $saleStamp;
    private $supportImageTypeList;
    private $photoSize;
    private $iconSize;
    private $saleSize;

    /**
     * UploadImageService constructor.
     * @param CheckImg $checkImg
     * @param Resize $resize
     * @param $supportImageTypeList
     * @param $photoSize
     * @param $iconSize
     * @param $saleSize
     */
    public function __construct(CheckImg $checkImg, Resize $resize, $supportImageTypeList, $photoSize, $iconSize, $saleSize)
    {
        $this->checkImg = $checkImg;
        $this->resize = $resize;
        $this->supportImageTypeList = $supportImageTypeList;
        $this->photoSize = $photoSize;
        $this->iconSize = $iconSize;
        $this->saleSize = $saleSize;
    }

    /**
     * @param mixed $imageRootDir
     */
    public function setWebDir($webDir)
    {
        $this->webDir = $webDir;
    }

    /**
     * @param mixed $saleStamp
     */
    public function setSaleStamp($saleStamp)
    {
        $this->saleStamp = $saleStamp;
    }

    /**
     * @return
     */
    public function uploadImage(UploadedFile $uploadedFile, $photoName = null)
    {
        $checkImg = $this->checkImg;
        $resize = $this->resize;
        $webDir = $this->webDir;
        $supportImageTypeList = $this->supportImageTypeList;
        $photoSize = $this->photoSize;
        $photoDirPath = $webDir . "photos/";

        if ($photoName == null) {
            $photoName = $checkImg->check($uploadedFile, $supportImageTypeList);
        }

        $uploadedFile->move($photoDirPath, $photoName);
        $result = $resize->resize($photoDirPath, $photoName, $photoSize);
        return $result;
    }

    /**
     * @return string
     */
    public function uploadIcon(UploadedFile $uploadedFile, $iconFileName = null)
    {
        $checkImg = $this->checkImg;
        $resize = $this->resize;
        $webDir = $this->webDir;
        $supportImageTypeList = $this->supportImageTypeList;
        $iconSize = $this->iconSize;
        $iconDirPath = $webDir . "icons/";

        if ($iconFileName == null) {
            $iconFileName = $checkImg->check($uploadedFile, $supportImageTypeList);
        }

        $uploadedFile->move($iconDirPath, $iconFileName);
        $result = $resize->resize($iconDirPath, $iconFileName, $iconSize);
        return $result;
    }

    /**
     * @return string
     */
    public function uploadSale(UploadedFile $uploadedFile = null, $saleFileName = null, $flag = null)
    {
        $checkImg = $this->checkImg;
        $resize = $this->resize;
        $webDir = $this->webDir;
        $supportImageTypeList = $this->supportImageTypeList;
        $saleSize = $this->saleSize;
        $saleDirPath = $webDir . "SalePhoto/";
        $iconDirPath = $webDir . "icons/";
        $saleStamp = $this->saleStamp;

        if ($uploadedFile == null) {
            copy($iconDirPath . $saleFileName, $saleDirPath . $saleFileName);

        } else {
            $saleFileName = $checkImg->check($uploadedFile, $supportImageTypeList);
            $uploadedFile->move($saleDirPath, $saleFileName);
        }
        if ($flag != null) {
            $result = $resize->resize($saleDirPath, $saleFileName, $saleSize, $saleStamp);
        } else {
            $result = $resize->resize($saleDirPath, $saleFileName, $saleSize);
        }
        return $result;
    }
}
