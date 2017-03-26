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
    private $supportImageTypeList;
    private $photoSize;
    private $iconSize;
    private $salePhotoSize;

    /**
     * UploadImageService constructor.
     * @param CheckImg $checkImg
     * @param Resize $resize
     * @param $supportImageTypeList
     * @param $photoSize
     * @param $iconSize
     * @param $salePhotoSize
     */
    public function __construct(CheckImg $checkImg, Resize $resize, $supportImageTypeList, $photoSize, $iconSize, $salePhotoSize)
    {
        $this->checkImg = $checkImg;
        $this->resize = $resize;
        $this->supportImageTypeList = $supportImageTypeList;
        $this->photoSize = $photoSize;
        $this->iconSize = $iconSize;
        $this->salePhotoSize = $salePhotoSize;
    }

    /**
     * @param mixed $imageRootDir
     */
    public function setWebDir($webDir)
    {
        $this->webDir = $webDir;
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
    public function uploadIcon(UploadedFile $uploadedFile, $model, $iconFileName = null)
    {
        $imageNameGenerator = $this->imageNameGenerator;
        $checkImg = $this->checkImg;

        if ($iconFileName == null) {
            $iconFileName = $model . $imageNameGenerator->generateName() . "." . $uploadedFile->getClientOriginalExtension();
        }

        $iconDirPath = $this->uploadImageRootDir . "../icons/";

        try {
            $checkImg->check($uploadedFile);
        } catch (\InvalidArgumentException $ex) {
            die("Image type error!");
        }

        try {
            $uploadedFile->move($iconDirPath, $iconFileName);
        } catch (\Exception $exception) {
            echo "Can not move file!";
            throw $exception;
        }
        $img = new ImageResize($iconDirPath . $iconFileName);
        $img->resizeToBestFit(250, 250);
        $img->save($iconDirPath . $iconFileName);

        return $iconFileName;
    }

    /**
     * @return string
     */
    public function uploadSale(UploadedFile $uploadedFile = null, Product $product)
    {
        $checkImg = $this->checkImg;
        $iconDirPath = $this->uploadImageRootDir . "../icons/";
        $saleDirPath = $this->uploadImageRootDir . "../SalePhoto/";
        $saleName = $product->getIconFileName();
        $saleStamp = $this->uploadImageRootDir . "../../source/SalePhoto/SalePhoto.png";
        $image = new ImageManager(array('driver' => 'gd'));

        if ($uploadedFile == null) {
            copy($iconDirPath . $saleName, $saleDirPath . $saleName);
        } else {
            try {
                $checkImg->check($uploadedFile);
            } catch (\InvalidArgumentException $ex) {
                die("Image type error!");
            }
            try {
                $uploadedFile->move($saleDirPath, $saleName);
            } catch (\Exception $exception) {
                echo "Can not move file!";
                throw $exception;
            }
        }
        $salePhotoFile = $saleDirPath . $saleName;
        $image->make($salePhotoFile)->resize(200, 130)->insert($saleStamp)->save();

        return true;
    }
}
