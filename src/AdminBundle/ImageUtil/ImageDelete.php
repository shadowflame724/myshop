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

    public function imageDelete($photoFileName, $iconFileName = null)
    {
        $photoDirPath = $this->uploadImageRootDir;
        $iconDirPath = $this->uploadImageRootDir . "../icons/";
        $iconFile = $iconDirPath . $iconFileName;
        $smallPhotoName = "small_" . $photoFileName;
        $photoFile = $photoDirPath . $photoFileName;
        $smallPhotoFile = $photoDirPath . $smallPhotoName;
        if (file_exists($iconFile)) {
            unlink($iconFile);
        }
        if (file_exists($smallPhotoFile)) {
            unlink($smallPhotoFile);
        }
        if (file_exists($photoFile)) {
            unlink($photoFile);
        }

        return true;
    }
}
