<?php
/**
 * Created by PhpStorm.
 * User: UserPC
 * Date: 24.03.2017
 * Time: 14:28
 */

namespace AdminBundle\ImageUtil;


use AdminBundle\DTO\UploadImageResult;
use Eventviva\ImageResize;
use Intervention\Image\ImageManager;

class Resize
{
    public function resize($dirPath, $imgName, $size, $saleStamp = null)
    {
        $imgFile = $dirPath . $imgName;
        $img = new ImageResize($imgFile);
        $height = $size[0];
        $width = $size[1];
        $img->resizeToBestFit($height, $width);
        if (strpos($dirPath, "photos") !== false) {
            $smallPhotoName = "small_" . $imgName;
            $img->save($dirPath . $smallPhotoName);
            $result = new UploadImageResult($imgName, $smallPhotoName);
            return $result;
        } else {
            if ($saleStamp != null) {
                $image = new ImageManager(array('driver' => 'gd'));
                $image->make($imgFile)->resize($width, $height)->insert($saleStamp)->save();
            } else $img->save($imgFile);
            return $imgName;
        }
    }
}
