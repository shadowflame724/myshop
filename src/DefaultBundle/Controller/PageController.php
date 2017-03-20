<?php
/**
 * Created by PhpStorm.
 * User: UserPC
 * Date: 20.03.2017
 * Time: 12:55
 */

namespace DefaultBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PageController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction($pageKey)
    {
        $page = $this->getDoctrine()->getRepository("DefaultBundle:Page")->findOneBy(["pageKey" => $pageKey]);
        if ($page == null) {
            throw $this->createNotFoundException();
        }

        return ['page' => $page];
    }

}
