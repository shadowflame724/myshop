<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Template
     */
    public function editorAction()
    {
        return [];
    }

    /**
     * @Template
     */
    public function exportDBAction()
    {
        $result = $this->get("myshop.admin_imex")->export();

        if ($result == true) {
            $this->addFlash(
                'success',
                'DB successful exported!'
            );
        } else {
            $this->addFlash(
                'success',
                'Something went wrong!'
            );
        }
        return $this->redirectToRoute("myshop.admin_editor");
    }
    /**
     * @Template
     */
    public function importDBAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('csv_file', FileType::class, ['label' => 'Choose CSV file:'])
            ->getForm();
        $form->handleRequest($request);
        if ($request->isMethod("POST"))
        {
            $data = $form->getData();
            /** @var UploadedFile $csvFile */
            $csvFile = $data['csv_file'];
            $flag = $request->get("flag");
            $result = $this->get("myshop.admin_imex")->import($csvFile->getRealPath(), $flag);
            if ($result == true) {
                $this->addFlash(
                    'success',
                    'DB successful imported!'
                );
            } else {
                $this->addFlash(
                    'success',
                    'Something went wrong!'
                );
            }
            return $this->redirectToRoute("myshop.admin_editor");
        }
        return ['form' => $form->createView()];
    }


    /**
     * @Template
     */
    public function cleanerAction()
    {
        $count = $this->get("myshop.admin_unused_image_delete")->deleteImg();
        if ($count != null) {
            $this->addFlash(
                'success',
                'Deleted: ' . $count . ' unused images!'
            );
        } else {
            $this->addFlash(
                'success',
                'NO unused images!'
            );
        }
        return $this->redirectToRoute("myshop.admin_editor");
    }

    public function loadDataAction()
    {
        $loader = $this->get("myshop.admin_loader");
        try {
            $loader->loadUser();
            $loader->loadManufacturer();
            $loader->loadCategory();
            $loader->loadProduct();
            $loader->loadPhoto();
            $loader->loadSaleProduct();
        } catch (\Exception $ex) {
            $this->addFlash("error", "something went wrong =( : " . $ex);
            return $this->redirectToRoute("myshop.admin_editor_product_list");
        }
        $this->addFlash("success", "Preview data success added!");
        return $this->redirectToRoute("myshop.admin_editor_product_list");
    }
}
