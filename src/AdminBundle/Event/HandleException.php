<?php

namespace AdminBundle\Event;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class HandleException
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        var_dump($event);
        /*
        if ($ex instanceof BaseApi){
            $response = [
                'error' =>[
                    'code' => $ex->getCode(),
                    'message' => $ex->getMessage()
                ]
            ];

            $response  = new Response();
            $response->setContent(\GuzzleHttp\json_encode($data));
            $event->setResponse($response);
        }
        */
    }
}