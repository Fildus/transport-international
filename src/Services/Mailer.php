<?php


namespace App\Services;


use Swift_Mailer;

class Mailer
{
    /**
     * @var array
     */
    private $sender = [
        'host' => 'imap.gmail.com',
        'port' => 465,
        'security' => 'SSL',
        'username' => 'musedulouvre@gmail.com',
        'password' => 'JuIbOjubnoocnu5'
    ];

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send($receiver, $message)
    {
        $transport = (new \Swift_SmtpTransport(
            $this->sender['host'],
            $this->sender['port'],
            $this->sender['security']
        ))
            ->setUsername($this->sender['username'])
            ->setPassword($this->sender['password']);

        $mailer = new Swift_Mailer($transport);

        $message = (new \Swift_Message('sujet'))
            ->setFrom($this->sender['username'])
            ->setTo($receiver)
            ->setBody($message, 'text/plain');

        $mailer->send($message);
    }

}