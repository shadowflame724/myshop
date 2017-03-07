<?php

namespace AdminBundle\Notification;

class Notification
{
    private static $count = 0;

    /**
     * @return int
     */
    public static function getCount()
    {
        return self::$count;
    }

    /**
     * @param int $count
     */
    public static function setCount($count)
    {
        self::$count = $count;
    }

    private static $history= [];

    /**
     * @return array
     */
    public static function getHistory()
    {
        return self::$history;
    }

    /**
     * @param array $history
     */
    public static function setHistory($history)
    {
        self::$history = $history;
    }

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
        self::$count++;
        self::$history[] = $body;

        var_dump(self::$count);
        $templating = $this->templating;

        if (self::$count >= 5){
            $htmlResult = $templating->render("::email.html.twig", [
                "historyList" => self::$history
            ]);
            $message = new \Swift_Message();
            $message->setTo("shadowflame724@gmail.com");
            $message->setFrom("myshop@gmail.com");
            $message->setBody($htmlResult, "text/html");

            $mailer = $this->mailer;
            $mailer->send($message);
            self::setCount(0);
            self::setHistory(null);
            return true;
        }
        self::setCount(self::$count);
        self::setHistory(self::$history);
        return true;
    }
}
