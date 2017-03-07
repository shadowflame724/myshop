<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use DefaultBundle\Entity\Manufacturer;
use DefaultBundle\Form\ManufacturerType;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class ManufacturerController extends Controller
{
    /**
     * @Template()
     */
    public function listAction()
    {
        $manufacturerList = $this->getDoctrine()->getRepository("DefaultBundle:Manufacturer")->findAll();
        return ["manufacturerList" => $manufacturerList];
    }

    /**
     * @Template()
     */
    public function addAction(Request $request)
    {
        $manufacturer = new Manufacturer();
        $form = $this->createForm(ManufacturerType::class, $manufacturer);
        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);
            /** @var ConstraintViolationList $errorList */
            $errorList = $this->get('validator')->validate($manufacturer);
            if ($errorList->count() > 0) {
                /** @var ConstraintViolation $error */
                foreach ($errorList as $error) {
                    $this->addFlash('error', $error->getMessage());
                }

                return $this->redirectToRoute("myshop.admin_editor_manufacturer_add");
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($manufacturer);
            $manager->flush();
            $this->addFlash(
                'success',
                'Manufacturer added!'
            );
            $notification = $this->get("myshop.admin_email_notification");
            $body = "Manufacturer " . $manufacturer->getCompany() . " added";
            $notification->sendAdminsEmail($body);
            return $this->redirectToRoute("myshop.admin_editor_manufacturer_list");
        }
        return ["form" => $form->createView()];
    }

    /**
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $manufacturer = $this->getDoctrine()->getRepository("DefaultBundle:Manufacturer")->find($id);
        $form = $this->createForm(ManufacturerType::class, $manufacturer);

        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);
            if ($form->isSubmitted())
            {
                /** @var ConstraintViolationList $errorList */
                $errorList = $this->get('validator')->validate($manufacturer);
                if ($errorList->count() > 0) {
                    /** @var ConstraintViolation $error */
                    foreach ($errorList as $error) {
                        $this->addFlash('error', $error->getMessage());
                    }

                    return $this->redirectToRoute("myshop.admin_editor_manufacturer_add");
                }
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($manufacturer);
                $manager->flush();
                $this->addFlash(
                    'success',
                    'Manufacturer edited!'
                );
                $notification = $this->get("myshop.admin_email_notification");
                $body = "Manufacturer " . $manufacturer->getCompany() . " edited";
                $notification->sendAdminsEmail($body);
                return $this->redirectToRoute("myshop.admin_editor_manufacturer_list");
            }
        }

        return [
            "form" => $form->createView(),
            "manufacturer" => $manufacturer
        ];
    }

    /**
     * @Template()
     */
    public function deleteAction(Request $request, $id)
    {
        $manufacturer = $this->getDoctrine()->getRepository("DefaultBundle:Manufacturer")->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($manufacturer);
        $manager->flush();
        $this->addFlash(
            'success',
            'Manufacturer deleted!'
        );
        $notification = $this->get("myshop.admin_email_notification");
        $body = "Manufacturer " . $manufacturer->getCompany() . " deleted";
        $notification->sendAdminsEmail($body);
        return $this->redirectToRoute("myshop.admin_editor_manufacturer_list");
    }
}
