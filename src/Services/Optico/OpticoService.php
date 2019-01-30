<?php

namespace App\Services\Optico;


use App\Entity\Client;
use App\Entity\ServedZone;

class OpticoService
{
    /**
     * @var $indicative int
     */
    private $indicative;

    /**
     * @param Client $client
     *
     * @return string|null
     */
    public function getNumber(Client $client): ?string
    {
        $this->getIndicative($client->getLocation()->getLocation());

        if ($client !== null) {
            if ($client->getContact() !== null && $client->getContact()->getPhone() !== null) {
                if ($this->indicative !== null){
                    $phone = '00'.$this->indicative.' '.$this->reformatNumber($client->getContact()->getPhone());
                }else{
                    $phone = $client->getContact()->getPhone();
                }

                $optico = new Optico('06f46a4bc4c2edd635373639de3c25b8');
                $optico->addPhone($this->reformatNumber($phone));
                $optico->sendView();
                $optico->getViewId();
                return $optico->getTrackingPhoneNumber($phone);
            }
            return null;
        }
        return null;
    }

    public function getNormalNumber(Client $client): ?string
    {
        $this->getIndicative($client->getLocation()->getLocation());

        if ($client !== null) {
            if ($client->getContact() !== null && $client->getContact()->getPhone() !== null) {
                if ($this->indicative !== null){
                    $phone = '00'.$this->indicative.' '.$this->reformatNumber($client->getContact()->getPhone());
                }else{
                    $phone = $client->getContact()->getPhone();
                }
                return $this->reformatNumber($phone);
            }
            return null;
        }
        return null;
    }

    public function getIndicative(ServedZone $servedZone): void
    {
        if ($servedZone->getIndicative() !== null && $servedZone->getIndicative() !== ''){
            $this->indicative = $servedZone->getIndicative();
        }elseif($servedZone->getParent() !== null){
            $this->getIndicative($servedZone->getParent());
        }
    }

    public function reformatNumber(?string $nbr)
    {
        if ($nbr !== null){
            $number = trim($nbr);
            $number = str_replace(' ','',$number);

            if ((int) $number[0] === 0){
                $number = substr($number, 1);
            }
            return $number;
        }
        return null;
    }
}