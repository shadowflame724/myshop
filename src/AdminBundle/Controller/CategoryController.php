<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use DefaultBundle\Entity\Category;
use DefaultBundle\Form\CategoryType;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class CategoryController extends Controller
{
    /**
     * @Template()
     */
    public function listAction()
    {
        $catlist = $this->getDoctrine()
            ->getManager()
            ->getRepository("DefaultBundle:Category")
            ->findAll();

        $res = [];

        foreach ($catlist as $cat) {
            $parentId = '#';
            if ($cat->getParentCategory() !== null) {
                $parentId = $cat->getParentCategory()->getId();
            }

            $res[] = [
                'id' => $cat->getId(),
                'parent' => $parentId,
                'text' => $cat->getName(),
                'state' => ['opened' => true]
            ];
        }

        return [
            "categoryList" => json_encode($res)
        ];
    }

    /**
     * @Template()
     */
    public function addAction(Request $request, $id)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            $manager = $this->getDoctrine()->getManager();

            if ($id !== null)
            {
                $parentCat = $this->getDoctrine()->getManager()->getRepository("DefaultBundle:Category")->find($id);
                $category->setParentCategory($parentCat);
            }
            /** @var ConstraintViolationList $errorList */
            $errorList = $this->get('validator')->validate($category);
            if ($errorList->count() > 0) {
                /** @var ConstraintViolation $error */
                foreach ($errorList as $error) {
                    $this->addFlash('error', $error->getMessage());
                }

                return $this->redirectToRoute("myshop.admin_editor_category_add");
            }
            $manager->persist($category);
            $manager->flush();
            $this->addFlash(
                'success',
                'Category added!'
            );
            $notification = $this->get("myshop.admin_email_notification");
            $body = "Category " . $category->getName() . " added";
            $notification->sendAdminsEmail($body);
            return $this->redirectToRoute("myshop.admin_editor_category_list");
        }
        return [
            "form" => $form->createView(),
            "id" => $id
        ];

    }

    /**
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository("DefaultBundle:Category")->find($id);
        $form = $this->createForm(CategoryType::class, $category);


        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($category);
                $manager->flush();
                $this->addFlash(
                    'success',
                    'Category edited!'
                );
                $notification = $this->get("myshop.admin_email_notification");
                $body = "Category " . $category->getName() . " edited";
                $notification->sendAdminsEmail($body);
                return $this->redirectToRoute("myshop.admin_editor_category_list");
            }
        }

        return [
            "form" => $form->createView(),
            "category" => $category
        ];
    }

    /**
     * @Template()
     */
    public function deleteAction(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository("DefaultBundle:Category")->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($category);
        $manager->flush();
        $this->addFlash(
            'success',
            'Category deleted!'
        );
        $notification = $this->get("myshop.admin_email_notification");
        $body = "Category " . $category->getName() . " deleted";
        $notification->sendAdminsEmail($body);
        return $this->redirectToRoute("myshop.admin_editor_category_list");
    }
}
