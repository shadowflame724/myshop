<?php

namespace AdminBundle\ImageUtil;


use AdminBundle\Controller\ProductController;
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
     * @var ImageNameGenerator
     */
    private $imageNameGenerator;
    private $uploadImageRootDir;
    private $smallPhotoName;
    private $supportImageTypeList;

    /**
     * @param mixed $imageRootDir
     */
    public function setUploadImageRootDir($imageRootDir)
    {
        $this->uploadImageRootDir = $imageRootDir;
    }

    public function __construct($checkImg, $imageNameGenerator, $imageTypeList)
    {
        $this->checkImg = $checkImg;
        $this->imageNameGenerator = $imageNameGenerator;
        $this->supportImageTypeList = $imageTypeList;
    }

    /**
     * @return UploadImageResult
     */
    public function uploadImage(UploadedFile $uploadedFile = null, $id, $photoFileName = null)
    {
        $imageNameGenerator = $this->imageNameGenerator;
        $checkImg = $this->checkImg;

        if ($photoFileName == null) {
            $photoFileName = $id . $imageNameGenerator->generateName() . "." . $uploadedFile->getClientOriginalExtension();

        }

        $photoDirPath = $this->uploadImageRootDir;
        if ($uploadedFile != null) {
            try {
                $checkImg->check($uploadedFile);
            } catch (\InvalidArgumentException $ex) {
                die("Image type error!");
            }
            try {
                $uploadedFile->move($photoDirPath, $photoFileName);
            } catch (\Exception $exception) {
                echo "Can not move file!";
                throw $exception;
            }
        }

        $img = new ImageResize($photoDirPath . $photoFileName);
        $height = $this->supportImageTypeList[0][0];
        $weight = $this->supportImageTypeList[0][1];
        $img->resizeToBestFit($height, $weight);
        $smallPhotoName = "small_" . $photoFileName;
        $img->save($photoDirPath . $smallPhotoName);
        $result = new UploadImageResult($photoFileName, $smallPhotoName);

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

        $iconDirPath = $this->uploadImageRootDir . "../" . "icons/";

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
}
