<?php
/**
 * Created by PhpStorm.
 * User: UserPC
 * Date: 07.03.2017
 * Time: 15:48
 */

namespace AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;

class LoadPrevDataController extends Controller
{
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
