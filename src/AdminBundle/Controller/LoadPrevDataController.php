<?php
/**
 * Created by PhpStorm.
 * User: UserPC
 * Date: 07.03.2017
 * Time: 15:48
 */

namespace AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LoadPrevDataController extends Controller
{
    public function loadDataAction()
    {
        $loader = $this->get("myshop.admin_loader");
        $loader->loadUser();
        $loader->loadManufacturer();
        $loader->loadCategory();
        $loader->loadProduct();

        $this->addFlash("success", "Preview data success added!");
        return $this->redirectToRoute("myshop.admin_editor_product_list");
    }
}
