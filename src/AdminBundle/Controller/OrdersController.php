<?php
/**
 * Created by PhpStorm.
 * User: hillel
 * Date: 23.04.17
 * Time: 10:40
 */

namespace AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class OrdersController extends Controller
{
    /**
     * @Template()
     */
    public function listAction()
    {
        $orders = $this->getDoctrine()->getManager()->getRepository("Order")->findAll();

        return [
            'orders' => $orders
        ];
    }

    public function resolveAction(Order $order)
    {
        $manager = $this->getDoctrine()->getManager();
        $order->setStatus(.....);

        return $this->redirectToRoute("orders_list");
    }

}