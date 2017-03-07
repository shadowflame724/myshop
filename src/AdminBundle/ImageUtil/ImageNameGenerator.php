<?php

namespace AdminBundle\ImageUtil;

class ImageNameGenerator
{
    public function generateName()
    {
        return rand(1000, 9999999);
    }
}