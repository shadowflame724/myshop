<?php

namespace AdminBundle\ImageUtil;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageNameGenerator
{
    public function generateName($uploadedFile)
    {
        if ($uploadedFile instanceof UploadedFile){
            $extension = $uploadedFile->getClientOriginalExtension();
        } else {
            $extension = pathinfo($uploadedFile)['extension'];
        }

    }
}