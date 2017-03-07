<?php

namespace AdminBundle\DTO;

class UploadImageResult
{
    private $photoFileName;
    private $smallPhotoName;

    public function __construct($photoFileName, $smallPhotoName)
    {
        $this->photoFileName = $photoFileName;
        $this->smallPhotoName = $smallPhotoName;
    }

    /**
     * @return string
     */
    public function getPhotoFileName()
    {
        return $this->photoFileName;
    }

    /**
     * @return string
     */
    public function getSmallPhotoName()
    {
        return $this->smallPhotoName;
    }
}