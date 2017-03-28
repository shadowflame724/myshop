<?php

namespace AdminBundle\Controller;

use DefaultBundle\Entity\SaleProduct;
use DefaultBundle\Form\SaleProductType;
use Intervention\Image\ImageManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class SaleProductController extends Controller
{
    /**
     * @Template()
     */
    public function listAction()
    {
        $saleProductList = $this->getDoctrine()->getManager()->getRepository("DefaultBundle:SaleProduct")->findAll();

        return [
            "saleProductList" => $saleProductList
        ];
    }

    /**
     * @Template()
     */
    public function addAction(Request $request, $id)
    {
        $photoFile = null;
        $saleFileName = null;
        $manager = $this->getDoctrine()->getManager();
        $product = $manager->getRepository("DefaultBundle:Product")->find($id);
        $test = $manager->createQuery("select s from DefaultBundle:SaleProduct s where s.product = '$id'")
            ->getResult();

        if ($test != null) {
            $manager->flush();
            $this->addFlash(
                'error',
                'This product already have sale!'
            );

            return $this->redirectToRoute("myshop.admin_editor_product_list");
        }
        if ($product == null) {
            return $this->createNotFoundException("Product not found!");
        }
        $saleProduct = new SaleProduct();
        $form = $this->createForm(SaleProductType::class, $saleProduct);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            /** @var ConstraintViolationList $errorList */
            $errorList = $this->get('validator')->validate($saleProduct);
            if ($errorList->count() > 0) {
                /** @var ConstraintViolation $error */
                foreach ($errorList as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
                return $this->redirectToRoute("myshop.admin_editor_saleproduct_add", ["id" => $id]);
            }
            $saleProduct->setProduct($product);
            $filesAr = $request->files->get("defaultbundle_saleproduct");

            if ($filesAr["photoFile"] !== null) {
                /** @var UploadedFile $photoFile */
                $photoFile = $filesAr["photoFile"];
            } else {
                $saleFileName = $product->getIconFileName();
            }
            $flag = $request->get("flag");
            $result = $this->get("myshop.admin_image_upload")->uploadSale($photoFile, $saleFileName, $flag);
            $saleProduct->setSalePhoto($result);

            $manager->persist($saleProduct);
            $manager->flush();
            $this->addFlash(
                'success',
                'SaleProduct added!');

            $notification = $this->get("myshop.admin_email_notification");
            $body = "Sale to product " . $product->getModel() . " added";
            $notification->sendAdminsEmail($body);
            return $this->redirectToRoute("myshop.admin_editor_saleproduct_list");
        }


        return ["form" => $form->createView(),
            "product" => $product];
    }

    /**
     * @Template()
     */
    public
    function editAction(Request $request, $id)
    {
        $manager = $this->getDoctrine()->getManager();
        //$product = $manager->getRepository("DefaultBundle:Product")->find($id);
        $saleProduct = $manager->getRepository("DefaultBundle:SaleProduct")->find($id);

        $form = $this->createForm(SaleProductType::class, $saleProduct);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            /** @var ConstraintViolationList $errorList */
            $errorList = $this->get('validator')->validate($saleProduct);
            if ($errorList->count() > 0) {
                /** @var ConstraintViolation $error */
                foreach ($errorList as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
                return $this->redirectToRoute("myshop.admin_editor_saleproduct_edit");
            }
            //$saleProduct->setProduct($product);
            $manager->persist($saleProduct);
            $manager->flush();
            $this->addFlash(
                'success',
                'SaleProduct added!'
            );
            $notification = $this->get("myshop.admin_email_notification");
            $body = "Saleproduct " . $saleProduct->getProduct()->getModel() . " edited";
            $notification->sendAdminsEmail($body);
            return $this->redirectToRoute("myshop.admin_editor_saleproduct_list");
        }
        return [
            "form" => $form->createView(),
            "saleProduct" => $saleProduct
        ];
    }

    /**
     * @Template()
     */
    public
    function deleteAction(Request $request, $id)
    {
        $saleProduct = $this->getDoctrine()->getRepository("DefaultBundle:SaleProduct")->find($id);
        $manager = $this->getDoctrine()->getManager();
        $salePhotoFile = $this->get("kernel")->getRootDir() . "/../web/SalePhoto/" . $saleProduct->getProduct()->getIconFileName();

        if (file_exists($salePhotoFile)) {
            unlink($salePhotoFile);
        }

        $manager->remove($saleProduct);
        $manager->flush();
        $this->addFlash(
            'success',
            'SaleProduct deleted!'
        );
        $notification = $this->get("myshop.admin_email_notification");
        $body = "Sale to product " . $saleProduct->getProduct()->getModel() . " deleted";
        $notification->sendAdminsEmail($body);
        return $this->redirectToRoute("myshop.admin_editor_saleproduct_list");
    }
}
