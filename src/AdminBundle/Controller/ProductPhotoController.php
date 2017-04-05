<?php

namespace AdminBundle\Controller;

use DefaultBundle\Entity\ProductPhoto;
use DefaultBundle\Form\ProductPhotoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;


class ProductPhotoController extends Controller
{
    /**
     * @Template()
     */
    public function listAction(Request $request, $id)
    {
        $product = $this->getDoctrine()->getManager()->getRepository("DefaultBundle:Product")->find($id);

        return [
            "product" => $product
        ];
    }

    /**
     * @Template()
     */
    public function addAction(Request $request, $id)
    {
        $manager = $this->getDoctrine()->getManager();
        $product = $manager->getRepository("DefaultBundle:Product")->find($id);

        if ($product == null) {
            return $this->createNotFoundException("Product not found!");
        }

        $photo = new ProductPhoto();
        $form = $this->createForm(ProductPhotoType::class, $photo);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            /** @var ConstraintViolationList $errorList */
            $errorList = $this->get('validator')->validate($photo);
            if ($errorList->count() > 0) {
                /** @var ConstraintViolation $error */
                foreach ($errorList as $error) {
                    $this->addFlash('error', $error->getMessage());
                }

                return $this->redirectToRoute("myshop.admin_editor_photo_add");
            }
            $filesAr = $request->files->get("defaultbundle_productphoto");

            /** @var UploadedFile $photoFile */
            $photoFile = $filesAr["photoFile"];

            $result = $this->get("myshop.admin_image_upload")->uploadImage($photoFile);

            $photo->setFileName($result->getPhotoFileName());
            $photo->setSmallFileName($result->getSmallPhotoName());
            $photo->setProduct($product);

            $manager->persist($photo);
            $manager->flush();

            $this->addFlash(
                'success',
                'Photo added!'
            );
            $notification = $this->get("myshop.admin_email_notification");
            $body = "Photo " . $photo->getFileName() . " added";
            $notification->sendAdminsEmail($body);
            return $this->redirectToRoute("myshop.admin_editor_photo_list", ["id" => $id]);
        }

        return [
            "form" => $form->createView(),
            "product" => $product
        ];
    }

    /**
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $photo = $this->getDoctrine()->getRepository("DefaultBundle:ProductPhoto")->find($id);
        $product = $photo->getProduct();
        $productId = $product->getId();

        $form = $this->createForm(ProductPhotoType::class, $photo, [
            "label" => "Photo",
            "required" => false,
            "mapped" => false
        ]);

        if ($request->isMethod("post")) {
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $manager = $this->getDoctrine()->getManager();
                if ($request->isMethod("POST")) {
                    $form->handleRequest($request);
                    /** @var ConstraintViolationList $errorList */
                    $errorList = $this->get('validator')->validate($photo);
                    if ($errorList->count() > 0) {
                        /** @var ConstraintViolation $error */
                        foreach ($errorList as $error) {
                            $this->addFlash('error', $error->getMessage());
                        }

                        return $this->redirectToRoute("myshop.admin_editor_photo_add");
                    }
                    $filesAr = $request->files->get("defaultbundle_productphoto");
                    if ($filesAr["photoFile"] !== null) {
                        $photoFileName = $photo->getFileName();
                        /** @var UploadedFile $photoFile */
                        $photoFile = $filesAr["photoFile"];

                        $result = $this->get("myshop.admin_image_upload")->uploadImage($photoFile, $photoFileName);

                        $photo->setFileName($result->getPhotoFileName());
                        $photo->setSmallFileName($result->getSmallPhotoName());
                        $photo->setProduct($product);

                    }
                    $manager->persist($photo);
                    $manager->flush();
                    $this->addFlash(
                        'success',
                        'Photo edited!'
                    );
                    $notification = $this->get("myshop.admin_email_notification");
                    $body = "Photo " . $photo->getFileName() . " edited";
                    $notification->sendAdminsEmail($body);
                    return $this->redirectToRoute("myshop.admin_editor_photo_list", ["id" => $productId]);
                }
            }
        }
        return [
            "form" => $form->createView(),
            "photo" => $photo,
            "product" => $product
        ];
    }

    /**
     * @Template()
     */
    public
    function deleteAction(Request $request, $id)
    {
        $photo = $this->getDoctrine()->getRepository("DefaultBundle:ProductPhoto")->find($id);
        $productId = $photo->getProduct()->getId();
        $manager = $this->getDoctrine()->getManager();
        $photoFileName = $photo->getFileName();
        $this->get("myshop.admin_image_delete")->imageDelete($photoFileName);
        $manager->remove($photo);
        $manager->flush();
        $this->addFlash(
            'success',
            'Photo deleted!'
        );
        $notification = $this->get("myshop.admin_email_notification");
        $body = "Photo " . $photo->getFileName() . " deleted";
        $notification->sendAdminsEmail($body);
        return $this->redirectToRoute("myshop.admin_editor_photo_list", ["id" => $productId]);
    }

}
