<?php

namespace AdminBundle\Controller;

use DefaultBundle\Entity\Page;
use DefaultBundle\Form\PageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class PageController extends Controller
{
    /**
     * @Template
     */
    public function listAction()
    {
        $pageList = $this->getDoctrine()->getManager()->getRepository("DefaultBundle:Page")->findAll();
        return [
            "pageList" => $pageList
        ];
    }

    /**
     * @Template
     */
    public function addAction(Request $request)
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);
        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            /** @var ConstraintViolationList $errorList */
            $errorList = $this->get('validator')->validate($page);
            if ($errorList->count() > 0) {
                /** @var ConstraintViolation $error */
                foreach ($errorList as $error) {
                    $this->addFlash('error', $error->getMessage());
                }

                return $this->redirectToRoute("myshop.admin_editor_page_add");
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($page);
            $manager->flush();
            $this->addFlash(
                'success',
                'Page added!'
            );
            $notification = $this->get("myshop.admin_email_notification");
            $body = "Page " . $page->getTitle() . " added";
            $notification->sendAdminsEmail($body);
            return $this->redirectToRoute("myshop.admin_editor_page_list");
        }
        return [
            "form" => $form->createView()
        ];
    }

    /**
     * @Template
     */
    public function editAction(Request $request, $id)
    {
        $manager = $this->getDoctrine()->getManager();
        $page = $manager->getRepository("DefaultBundle:Page")->find($id);
        $form = $this->createForm(PageType::class, $page);
        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            /** @var ConstraintViolationList $errorList */
            $errorList = $this->get('validator')->validate($page);
            if ($errorList->count() > 0) {
                /** @var ConstraintViolation $error */
                foreach ($errorList as $error) {
                    $this->addFlash('error', $error->getMessage());
                }

                return $this->redirectToRoute("myshop.admin_editor_page_add");
            }

            $manager->persist($page);
            $manager->flush();
            $this->addFlash(
                'success',
                'Page added!'
            );
            $notification = $this->get("myshop.admin_email_notification");
            $body = "Page " . $page->getTitle() . " added";
            $notification->sendAdminsEmail($body);
            return $this->redirectToRoute("myshop.admin_editor_page_list");
        }
        return [
            "form" => $form->createView(),
            "page" => $page
        ];
    }

    public function deleteAction(Request $request, $id)
    {
        $manager = $this->getDoctrine()->getManager();
        $page = $manager->getRepository("DefaultBundle:Page")->find($id);
        $manager->remove($page);
        $manager->flush();
        $this->addFlash(
            'success',
            'Page deleted!'
        );
        $notification = $this->get("myshop.admin_email_notification");
        $body = "Page " . $page->getTitle() . " deleted";
        $notification->sendAdminsEmail($body);
        return $this->redirectToRoute("myshop.admin_editor_page_list");
    }
}
