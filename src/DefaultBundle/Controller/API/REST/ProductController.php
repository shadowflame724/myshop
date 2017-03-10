<?php

namespace DefaultBundle\Controller\API\REST;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductController extends Controller
{

    public function productInfoAction($id = null)
    {
        /** @var \DefaultBundle\Entity\Product $productList */
        $productList = $this->getDoctrine()->getManager()->getRepository("DefaultBundle:Product")->findAll();
        $iconDir = "http://127.0.0.1:8000/web/icons";

        $productArray = [];
        foreach ($productList as $product){

            $productArray[] = [
                'model' => $product->getModel(),
                'price' => $product->getPrice(),
                'description' => $product->getDescription(),
                'date' => $product->getAddDate()->format('d.m.Y'),
                'company' => $product->getManufacturer()->getCompany(),
                'category' => $product->getCategory()->getName(),
                'iconFileName' => $iconDir . $product->getIconFileName()
            ];
        }

        if ($id != 0)
        {
            /** @var \DefaultBundle\Entity\Product $productList */
            $product = $this->getDoctrine()->getManager()->getRepository("DefaultBundle:Product")->find($id);
            $productArray = [
                'model' => $product->getModel(),
                'price' => $product->getPrice(),
                'description' => $product->getDescription(),
                'date' => $product->getAddDate()->format('d.m.Y'),
                'company' => $product->getManufacturer()->getCompany(),
                'category' => $product->getCategory()->getName(),
                'iconFileName' => $iconDir . $product->getIconFileName()
            ];
        }
        $response = new JsonResponse($productArray);
        return $response;
    }
}
