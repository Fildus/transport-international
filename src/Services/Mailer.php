<?php


namespace App\Services;


use Swift_Mailer;

class Mailer
{
    /**
     * @var array
     */
    private static $sender = [
        'host' => 'imap.gmail.com',
        'port' => 465,
        'security' => 'SSL',
        'username' => 'musedulouvre@gmail.com',
        'password' => 'JuIbOjubnoocnu5'
    ];

    /**
     * @param $receiver
     * @param $message
     */
    public function send($receiver, $message): void
    {
        $transport = (new \Swift_SmtpTransport(
            getenv('HOST'),
            getenv('PORT'),
            getenv('SECURITY')
        ))
            ->setUsername(getenv('USERNAME'))
            ->setPassword(getenv('PASSWORD'));

        $mailer = new Swift_Mailer($transport);

        $message = (new \Swift_Message('Nouvel utilisateur'))
            ->setFrom(getenv('USERNAME'))
            ->setTo($receiver)
            ->setBody($message, 'text/plain')
            ->setContentType('text/html');

        $mailer->send($message);
    }

}