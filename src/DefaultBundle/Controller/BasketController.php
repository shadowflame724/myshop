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
     * !!!ДЕЛАЙ ОПИСАНИЕ!!!
     * создать ентити
     * продумать поля, связи (не товар, а копия(товар заказа)), ассерты
     * создать ентити товар заказа
     * создать заказ
     * задать дату
     * задать статус заказа
     * создание заказа(при первом добавлении товара, 1 обращении к корзине и тд.)
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