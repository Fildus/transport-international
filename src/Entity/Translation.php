<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TranslationRepository")
 */
class Translation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $fr;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $frSlug;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $en;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $enSlug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $es;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $esSlug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $de;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $deSlug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $it;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $itSlug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $pt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ptSlug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $be;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $beSlug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ad;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $adSlug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ro;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $roSlug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ma;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $maSlug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ci;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ciSlug;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFr()
    {
        return $this->fr;
    }

    /**
     * @param mixed $fr
     * @return Translation
     */
    public function setFr($fr)
    {
        $this->fr = $fr;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFrSlug()
    {
        return $this->frSlug;
    }

    /**
     * @param mixed $frSlug
     * @return Translation
     */
    public function setFrSlug($frSlug)
    {
        $this->frSlug = $frSlug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEn()
    {
        return $this->en;
    }

    /**
     * @param mixed $en
     * @return Translation
     */
    public function setEn($en)
    {
        $this->en = $en;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnSlug()
    {
        return $this->enSlug;
    }

    /**
     * @param mixed $enSlug
     * @return Translation
     */
    public function setEnSlug($enSlug)
    {
        $this->enSlug = $enSlug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEs()
    {
        return $this->es;
    }

    /**
     * @param mixed $es
     * @return Translation
     */
    public function setEs($es)
    {
        $this->es = $es;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEsSlug()
    {
        return $this->esSlug;
    }

    /**
     * @param mixed $esSlug
     * @return Translation
     */
    public function setEsSlug($esSlug)
    {
        $this->esSlug = $esSlug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDe()
    {
        return $this->de;
    }

    /**
     * @param mixed $de
     * @return Translation
     */
    public function setDe($de)
    {
        $this->de = $de;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeSlug()
    {
        return $this->deSlug;
    }

    /**
     * @param mixed $deSlug
     * @return Translation
     */
    public function setDeSlug($deSlug)
    {
        $this->deSlug = $deSlug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIt()
    {
        return $this->it;
    }

    /**
     * @param mixed $it
     * @return Translation
     */
    public function setIt($it)
    {
        $this->it = $it;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getItSlug()
    {
        return $this->itSlug;
    }

    /**
     * @param mixed $itSlug
     * @return Translation
     */
    public function setItSlug($itSlug)
    {
        $this->itSlug = $itSlug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPt()
    {
        return $this->pt;
    }

    /**
     * @param mixed $pt
     * @return Translation
     */
    public function setPt($pt)
    {
        $this->pt = $pt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPtSlug()
    {
        return $this->ptSlug;
    }

    /**
     * @param mixed $ptSlug
     * @return Translation
     */
    public function setPtSlug($ptSlug)
    {
        $this->ptSlug = $ptSlug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBe()
    {
        return $this->be;
    }

    /**
     * @param mixed $be
     * @return Translation
     */
    public function setBe($be)
    {
        $this->be = $be;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBeSlug()
    {
        return $this->beSlug;
    }

    /**
     * @param mixed $beSlug
     * @return Translation
     */
    public function setBeSlug($beSlug)
    {
        $this->beSlug = $beSlug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAd()
    {
        return $this->ad;
    }

    /**
     * @param mixed $ad
     * @return Translation
     */
    public function setAd($ad)
    {
        $this->ad = $ad;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdSlug()
    {
        return $this->adSlug;
    }

    /**
     * @param mixed $adSlug
     * @return Translation
     */
    public function setAdSlug($adSlug)
    {
        $this->adSlug = $adSlug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRo()
    {
        return $this->ro;
    }

    /**
     * @param mixed $ro
     * @return Translation
     */
    public function setRo($ro)
    {
        $this->ro = $ro;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoSlug()
    {
        return $this->roSlug;
    }

    /**
     * @param mixed $roSlug
     * @return Translation
     */
    public function setRoSlug($roSlug)
    {
        $this->roSlug = $roSlug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMa()
    {
        return $this->ma;
    }

    /**
     * @param mixed $ma
     * @return Translation
     */
    public function setMa($ma)
    {
        $this->ma = $ma;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaSlug()
    {
        return $this->maSlug;
    }

    /**
     * @param mixed $maSlug
     * @return Translation
     */
    public function setMaSlug($maSlug)
    {
        $this->maSlug = $maSlug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCi()
    {
        return $this->ci;
    }

    /**
     * @param mixed $ci
     * @return Translation
     */
    public function setCi($ci)
    {
        $this->ci = $ci;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCiSlug()
    {
        return $this->ciSlug;
    }

    /**
     * @param mixed $ciSlug
     * @return Translation
     */
    public function setCiSlug($ciSlug)
    {
        $this->ciSlug = $ciSlug;
        return $this;
    }

    public function __toString()
    {
        if (isset($GLOBALS['request']) && $GLOBALS['request']) {
            $locale = $GLOBALS['request']->getLocale();
            if ($this->__get($locale) !== null) {
                return (string)$this->__get($locale);
            }
            return (string)$this->getFr();
        }
        return null;
    }

    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        $translation = $this->$method();
        if ($translation !== null) {
            return $translation;
        }
        return null;
    }

    public function getSlug()
    {
        if (isset($GLOBALS['request']) && $GLOBALS['request']) {
            $locale = $GLOBALS['request']->getLocale();
            if ($this->__get($locale.'Slug') !== null) {
                return (string)$this->__get($locale.'Slug');
            }
            return (string)$this->getFr();
        }
        return null;
    }
}
