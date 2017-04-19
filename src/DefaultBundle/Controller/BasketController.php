<?php
/**
 * Created by PhpStorm.
 * User: hillel
 * Date: 19.04.17
 * Time: 19:34
 */

namespace DefaultBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BasketController extends Controller
{

    /**
     * @Template
     */
    public function historyOrderAction()
    {
        // twig EXTENSION повтори
        // доставка это тоже товар
        $customer = $this->getUser();
        $orders = $this->getDoctrine()->getRepository("Order")->findBy(["customer" => $customer]);

        return ["orders" => $orders];
    }
}