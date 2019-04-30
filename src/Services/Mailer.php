<?php


namespace App\Services;


use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Component\Config\Definition\Exception\Exception;

class Mailer
{
    /**
     * @param $receiver
     * @param $message
     */
    public function send($receiver, $message): void
    {
        try{
            $transport = (new Swift_SmtpTransport(
                getenv('HOST'),
                getenv('PORT'),
                getenv('SECURITY')
            ))
                ->setUsername(getenv('USERNAME'))
                ->setPassword(getenv('PASSWORD'));

            $mailer = new Swift_Mailer($transport);

            $message = (new Swift_Message('Nouvel utilisateur'))
                ->setFrom(getenv('USERNAME'))
                ->setTo($receiver)
                ->setBody($message, 'text/plain')
                ->setContentType('text/html');

            $mailer->send($message);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }

    }

}