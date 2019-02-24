<?php

namespace App\Controller\Back;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class HomeController
 * @package App\Controller\backOffice
 * @Route("/admin", name="_admin")
 */
class CsvController extends AbstractController
{
    /**
     * @Route("/csv", name="_csv")
     * @param CacheInterface      $cache
     *
     * @param ClientRepository    $clientRepository
     *
     * @param SerializerInterface $serializer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function index(CacheInterface $cache, ClientRepository $clientRepository, SerializerInterface $serializer): \Symfony\Component\HttpFoundation\Response
    {
        $query = $cache->get('last_query');

        $clients = $clientRepository->getAllClients($query)->getQuery()->getResult();

        $data = [[
            'id', 'SIret', 'Dénomination sociale', 'Nom commercial', 'Forme juridique', 'Chiffres d\'affaires', 'Nombre d\'employés', 'Nombre d\'établissements', 'slug',
            'Localisation', 'Adresse', 'code postale', 'Ville',
            'téléphone', 'fax', 'contact', 'site web',
            'transport', 'logistique', 'affrètement', 'voyageur', 'déménagement', 'stockage', 'location avec chauffeur', 'taxis',

        ]];

        $rootPath = $this->getParameter('kernel.project_dir') . '/public/csv/';
        $fileName = 'clients.csv';

        umask(0);

        /**
         * @var $client Client
         */
        foreach ($clients as $client) {
            $lI = $client->getLegalInformation();
            $l = $client->getLocation();
            $c = $client->getContact();
            $cB = $client->getCoreBusiness();
            $data = [
                /* Informations légales */
                $lI->getId(),
                $lI->getSiret(),
                $lI->getCorporateName(),
                $lI->getCompanyName(),
                $lI->getLegalForm(),
                $lI->getTurnover(),
                $lI->getWorkforceNbr(),
                $lI->getEstablishmentsNbr(),
                $lI->getSlug(),

                /* Localisation */
                $l->getLocation()->__toString(),
                $l->getAddress(),
                $l->getPostalCode(),
                $l->getCity(),

                /* Contact */
                $c->getPhone(),
                $c->getFax(),
                $c->getContact(),
                $c->getWebSite(),

                /* Business principal */
                $cB->getTransport() ? 'oui' : 'non',
                $cB->getLogistics() ? 'oui' : 'non',
                $cB->getCharter() ? 'oui' : 'non',
                $cB->getTravelers() ? 'oui' : 'non',
                $cB->getRelocation() ? 'oui' : 'non',
                $cB->getStorage() ? 'oui' : 'non',
                $cB->getRentalWithDriver() ? 'oui' : 'non',
                $cB->getTaxis() ? 'oui' : 'non',
            ];
        }

//        file_put_contents(
//            $rootPath.$fileName,
//            $serializer->encode($data, 'csv')
//        );

        return (new BinaryFileResponse($rootPath . $fileName))
            ->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName);
    }
}
