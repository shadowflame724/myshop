<?php

namespace AdminBundle\ImageUtil;


class ImageDelete
{
    private $webDir;

    /**
     * @param mixed $webDir
     */
    public function setWebDir($webDir)
    {
        $this->webDir = $webDir;
    }


    public function imageDelete($photoFileName, $iconFileName = null)
    {
        $photoDirPath = $this->webDir;
        $iconDirPath = $this->webDir . "icons/";
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
