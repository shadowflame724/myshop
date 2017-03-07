<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\User;
use AdminBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Template()
     */
    public function listAction()
    {
        $userList = $this->getDoctrine()
            ->getManager()
            ->getRepository("AdminBundle:User")
            ->findAll();

        return ["userList" => $userList];
    }

    /**
     * @Template
     */
    public function addAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        if ($request->isMethod("post")) {
            $form->handleRequest($request);

            $plainPassword = $user->getPlainPassword();

            $password = $this->get("security.password_encoder")->encodePassword($user, $plainPassword);
            $user->setPassword($password);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'New user added!'
            );
            $notification = $this->get("myshop.admin_email_notification");
            $body = "User " . $user->getUsername() . " added";
            $notification->sendAdminsEmail($body);

            return $this->redirectToRoute("myshop.admin_editor_user_list");

        }
        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository("AdminBundle:User")->find($id);

        $form = $this->createForm(UserType::class, $user);
        $form->add("oldPassword");
        $manager = $this->getDoctrine()->getManager();

        if ($request->isMethod("post")) {
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $oldPassword = $user->getOldPassword();
                $oldPassword = $this->get("security.password_encoder")->isPasswordValid($user, $oldPassword);
                $password = $user->getPassword();

                if ($oldPassword == true) {
                    $manager->persist($user);
                    $manager->flush();
                    $this->addFlash(
                        'success',
                        'User changed!'
                    );
                    $notification = $this->get("myshop.admin_email_notification");
                    $body = "User " . $user->getUsername() . " edited";
                    $notification->sendAdminsEmail($body);
                    return [
                        "form" => $form->createView(),
                        "user" => $user
                    ];
                } else

                    $this->addFlash(
                        'error',
                        'oldPassword is wrong!'
                    );
                return $this->redirectToRoute("myshop.admin_editor_user_list");
            }
        }
        return [
            "form" => $form->createView(),
            "user" => $user
        ];
    }

    /**
     * @Template()
     */
    public function deleteAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository("AdminBundle:User")->find($id);
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($user);
        $manager->flush();
        $this->addFlash(
            'success',
            'User deleted!'
        );
        $notification = $this->get("myshop.admin_email_notification");
        $body = "User " . $user->getUsername() . " deleted";
        $notification->sendAdminsEmail($body);
        return $this->redirectToRoute("myshop.admin_editor_user_list");
    }

}
