<?php
namespace DefaultBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Controller\CategoryController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function menuListAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $categoryList = $manager
            ->createQuery("select p from DefaultBundle:Category p where p.parentCategory is null")
            ->getResult();
        //$catId = $request->get("catId");
        //$manId = $request->get("manId");
        $manufacturerList = $manager->getRepository("DefaultBundle:Manufacturer")->findAll();
        /*
         * menu
         *
        if ($catId == null && $manId == null) {
            $manager = $this->getDoctrine()->getManager();
            $productList = $manager
                ->createQuery("select p, c, m, ph from DefaultBundle:Product p join p.category c join p.manufacturer m join p.photos ph")
                ->getResult();
        } elseif ($catId !== null && $manId == null) {
            $manager = $this->getDoctrine()->getManager();
            $productList = $manager
                ->createQuery("select p, c, m, ph from DefaultBundle:Product p join p.category c join p.manufacturer m join p.photos ph where c.id = '$catId'")
                ->getResult();
        } else {
            $manager = $this->getDoctrine()->getManager();
            $productList = $manager
                ->createQuery("select p, c, m, ph from DefaultBundle:Product p join p.category c join p.manufacturer m join p.photos ph where m.id = '$manId'")
                ->getResult();
        }
        */
        return $this->render('@Default/layout.html.twig',
            [
                "categoryList" => $categoryList,
                "manufacturerList" => $manufacturerList,
            ]);
    }

    /**
     * @Template
     */
    public function indexAction(Request $request, $id = null)
    {
        $manager = $this->getDoctrine()->getManager();
        $productList = $manager->getRepository("DefaultBundle:Product")->findAll();
        return [
            "productList" => $productList,
        ];
    }

    /**
     * @Template
     */
    public function singleAction(Request $request, $id)
    {
        $product = $this->getDoctrine()->getManager()->getRepository("DefaultBundle:Product")->find($id);
        $productCategory = $product->getCategory();
        $similarProductList = $this->getDoctrine()->getManager()
            ->createQuery("select p from DefaultBundle:Product p where p.category = '$productCategory' and p.id != '$id'")
            ->getResult();

        return [
            "product" => $product,
            "similarProductList" => $similarProductList,
        ];
    }
}
