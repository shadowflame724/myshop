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
}
