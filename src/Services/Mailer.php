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
            self::$sender['host'],
            self::$sender['port'],
            self::$sender['security']
        ))
            ->setUsername(self::$sender['username'])
            ->setPassword(self::$sender['password']);

        $mailer = new Swift_Mailer($transport);

        $message = (new \Swift_Message('Nouvel utilisateur'))
            ->setFrom(self::$sender['username'])
            ->setTo($receiver)
            ->setBody($message, 'text/plain')
            ->setContentType('text/html');

        $mailer->send($message);
    }

}