<?php
namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use DefaultBundle\Entity\Product;
use DefaultBundle\Form\ProductType;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;


class ProductController extends Controller
{
    /**
     * @Template()
     */
    public function listAction()
    {
        $productList = $this->getDoctrine()->getRepository("DefaultBundle:Product")->findAll();
        return ["productList" => $productList];
    }

    /**
     * @Template()
     */
    public function addAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            /** @var ConstraintViolationList $errorList */
            $errorList = $this->get('validator')->validate($product);
            if ($errorList->count() > 0) {
                /** @var ConstraintViolation $error */
                foreach ($errorList as $error) {
                    $this->addFlash('error', $error->getMessage());
                }

                return $this->redirectToRoute("myshop.admin_editor_product_add");
            }
            $filesAr = $request->files->get("defaultbundle_product");

            /** @var UploadedFile $iconFileName */
            $photoFile = $filesAr["iconFileName"];


            $iconFileName = $this->get("myshop.admin_image_upload")->uploadIcon($photoFile, $product->getModel());

            $product->setIconFileName($iconFileName);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($product);
            $manager->flush();

            $this->addFlash(
                'success',
                'Product added!'
            );
            $notification = $this->get("myshop.admin_email_notification");
            $body = "Product " . $product->getModel() . " added";
            $notification->sendAdminsEmail($body);
            return $this->redirectToRoute("myshop.admin_editor_product_list");
        }
        return ["form" => $form->createView()];
    }

    /**
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $product = $this->getDoctrine()->getRepository("DefaultBundle:Product")->find($id);

        $form = $this->createForm(ProductType::class, $product);
        if ($request->isMethod("post")) {
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                {
                    /** @var ConstraintViolationList $errorList */
                    $errorList = $this->get('validator')->validate($product);
                    if ($errorList->count() > 0) {
                        /** @var ConstraintViolation $error */
                        foreach ($errorList as $error) {
                            $this->addFlash('error', $error->getMessage());
                        }

                        return $this->redirectToRoute("myshop.admin_editor_product_add");
                    }
                    $manager = $this->getDoctrine()->getManager();
                    $filesAr = $request->files->get("defaultbundle_product");
                    if ($filesAr["iconFileName"] !== null) {
                        $iconFileName = $product->getIconFileName();
                        /** @var UploadedFile $iconFileName */
                        $photoFile = $filesAr["iconFileName"];

                        $iconFileName = $this->get("myshop.admin_image_upload")->uploadIcon($photoFile, $product->getModel(), $iconFileName);

                        $product->setIconFileName($iconFileName);
                    }
                    $manager->persist($product);
                    $manager->flush();
                    $this->addFlash(
                        'success',
                        'Product changed!'
                    );
                    $notification = $this->get("myshop.admin_email_notification");
                    $body = "Product " . $product->getModel() . " edited";

                    //for ($i = 0; $i <= 5; $i++) {
                    $notification->sendAdminsEmail($body);

                    return $this->redirectToRoute("myshop.admin_editor_product_list");
                }
            }
        }
        return [
            "form" => $form->createView(),
            "product" => $product
        ];

    }

    /**
     * @Template()
     */
    public function deleteAction(Request $request, $id)
    {
        $manager = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository("DefaultBundle:Product")->find($id);
        $photos = $product->getPhotos();

        foreach ($photos as $photo) {
            $photoFileName = $photo->getFileName();
            $this->get("myshop.admin_image_delete")->imageDelete($photoFileName, $product->getIconFileName());
            $manager->remove($photo);
        }

        $manager->remove($product);
        $manager->flush();
        $this->addFlash(
            'success',
            'Product deleted!'
        );
        $notification = $this->get("myshop.admin_email_notification");
        $body = "Product " . $product->getModel() . " deleted";
        $notification->sendAdminsEmail($body);
        return $this->redirectToRoute("myshop.admin_editor_product_list");
    }
}

