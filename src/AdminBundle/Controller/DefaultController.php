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
        $dbNameList = $this->getDoctrine()
            ->getManager()
            ->createQuery("select p.fileName from DefaultBundle:ProductPhoto p ")
            ->getResult();
/*
        $count = $this->get("myshop.admin_unused_image_delete")->deleteImg($dbNameList);
        if ($count != null) {
            $this->addFlash(
                'success',
                'Deleted: ' . $count . ' unused photos!'
            );
        }
*/
        return [];
    }

}
