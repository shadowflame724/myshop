<?php

namespace AdminBundle\ImageUtil;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class CheckImg
{
    private $supportImageTypeList;

    public function __construct($imageTypeList)
    {
        $this->supportImageTypeList = $imageTypeList;
    }

    public function check($photoFile)
    {
        $checkTrue = false;
        if ($photoFile instanceof UploadedFile) {
            $mimeType = $photoFile->getClientMimeType();
            $fileExt = $photoFile->getClientOriginalExtension();
        } elseif(is_string($photoFile)) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer(file_get_contents($photoFile));
            $fileExt = pathinfo($photoFile)['extension'];
        }
        foreach ($this->supportImageTypeList as $imgType) {
            if ($mimeType == $imgType[1]) {
                $checkTrue = true;
            }
        }
        if ($checkTrue !== true) {
            throw new \InvalidArgumentException("Mime type is blocked!");
        }
        $checkTrue = false;
        foreach ($this->supportImageTypeList as $imgType) {
            if ($fileExt == $imgType[0]) {
                $checkTrue = true;
            }
        }
        if ($checkTrue == false) {
            throw new \InvalidArgumentException("Extension is blocked!");
        }
        return true;
    }
}
