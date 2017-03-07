<?php

namespace AdminBundle\ImageUtil;


class ImageDelete
{
    private $uploadImageRootDir;

    /**
     * @param mixed $uploadImageRootDir
     */
    public function setUploadImageRootDir($uploadImageRootDir)
    {
        $this->uploadImageRootDir = $uploadImageRootDir;
    }

    public function imageDelete($photoFileName)
    {
        $photoDirPath = $this->uploadImageRootDir;
        $smallPhotoName = "small_" . $photoFileName;
        $photoFile = $photoDirPath . $photoFileName;
        $smallPhotoFile = $photoDirPath . $smallPhotoName;
        if(file_exists($smallPhotoFile)){
            unlink($smallPhotoFile);
        }
        if(file_exists($photoFile)){
            unlink($photoFile);
        }

        return true;
    }
}
