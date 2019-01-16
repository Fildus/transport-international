<?php

namespace App\Entity\Search;


class UserSearch
{
    /**
     * @var $mail string
     */
    private $mail;

    /**
     * @var $role string
     */
    private $role;

    /**
     * @return string
     */
    public function getMail(): ?string
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     * @return UserSearch
     */
    public function setMail(string $mail): UserSearch
    {
        $this->mail = $mail;
        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param string $role
     * @return UserSearch
     */
    public function setRole(string $role): UserSearch
    {
        $this->role = $role;
        return $this;
    }
}