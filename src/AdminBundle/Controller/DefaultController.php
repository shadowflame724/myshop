<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
    public function cleanerAction()
    {
        $photosNameList = $this->getDoctrine()
            ->getManager()
            ->createQuery("select p.fileName from DefaultBundle:ProductPhoto p ")
            ->getResult();
        $iconsNameList = $this->getDoctrine()
            ->getManager()
            ->createQuery("select p.iconFileName from DefaultBundle:Product p ")
            ->getResult();

        $count = $this->get("myshop.admin_unused_image_delete")->deleteImg($photosNameList, $iconsNameList);
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
        } catch (Exception $ex) {
            $this->addFlash("error", "something went wrong =( : " . $ex);
            return $this->redirectToRoute("myshop.admin_editor_product_list");
        }
        $this->addFlash("success", "Preview data success added!");
        return $this->redirectToRoute("myshop.admin_editor_product_list");
    }
}
