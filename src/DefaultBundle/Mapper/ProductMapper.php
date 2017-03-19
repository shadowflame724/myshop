<?php
/**
 * Created by PhpStorm.
 * User: hillel
 * Date: 19.03.17
 * Time: 11:21
 */

namespace DefaultBundle\ProductMapper;


use DefaultBundle\Entity\Product;
use DefaultBundle\Entity\ProductPhoto;

class ProductMapper
{

    public function arrayToProd(array $data)
    {
        $product = new Product();
        $product->setModel($data["Model"]);
        .......
        foreach ($data["Images"] as $image)
        {
            $photo = new ProductPhoto();
            $photo->setProduct($product);
            ..........
        }
    }
}