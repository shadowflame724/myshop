<?php

namespace AdminBundle\Notification;

class Notification
{
    private $mailer;
    private $templating;

    public function __construct($mailer, $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @Template
     */
    public function sendAdminsEmail($body)
    {
        $templating = $this->templating;
        $mailer = $this->mailer;

        $htmlResult = $templating->render("::email.html.twig", [
            "historyList" => $body
        ]);
        $message = new \Swift_Message();
        $message->setTo("shadowflame724@gmail.com");
        $message->setFrom("myshop@gmail.com");
        $message->setBody($htmlResult, "text/html");

        $mailer->send($message);

        return true;
    }
}
