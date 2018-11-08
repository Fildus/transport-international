<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Services\ExtractDb;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

class EssaisController extends AbstractController
{
    /**
     * @Route("/home/{id}", name="test", defaults={"id"=1})
     * @param ClientRepository $clientRepository
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ClientRepository $clientRepository, Request $request, EntityManagerInterface $manager, $id)
    {
//        $client = new Client();
        $client = $clientRepository->find($id);
        $form = $this->createForm(ClientType::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($client);
            $manager->flush();
        }

        return $this->render('test.html.twig', [
            'form' => $form->createView(),
            'structureActivities' => Yaml::parseFile($this->container->get('parameter_bag')->get('kernel.project_dir') . '/src/Data/activities.yaml'),
            'structureServedZone' => Yaml::parseFile($this->container->get('parameter_bag')->get('kernel.project_dir') . '/src/Data/servedZone.yaml')
        ]);
    }

    /**
     * @Route("/dumpDB", name="dumpDB")
     */
    public function dumpDB()
    {
        $pays = ExtractDb::extract(ExtractDb::PAYS, null, 0);
        $regions = ExtractDb::extract(ExtractDb::REGION, null, 0);
        $departements = ExtractDb::extract(ExtractDb::DEPARTEMENT, null, 0);

        $formatedCountry = [];

        foreach ($pays as $kp => $vp) {
            $formatedCountry[$vp['id_pays']] = [
                'nom' => $vp['nom_GB'],
                'id_pays' => $vp['id_pays'],
                'regions' => []
            ];
        }

        foreach ($regions as $kreg => $vreg) {
            $formatedCountry[$vreg['id_pays']]['regions'][$vreg['id_region']] = [
                'nom' => $vreg['nom_GB'],
                'id_region' => $vreg['id_region'],
                'departements' => []
            ];
        }

        foreach ($departements as $kdep => $vdep) {
            foreach ($formatedCountry as $country) {
                if (isset($country['id_pays'])) {
                    $idPays = (int)$country['id_pays'];
                }
                if (isset($country['regions'])) {
                    foreach ($country['regions'] as $region) {
                        if (isset($region['id_region']) && (int)$region['id_region'] === (int)$vdep['id_region']) {
                            $formatedCountry[$idPays]['regions'][$region['id_region']]['departements'][$vdep['id_dept']] = [
                                'nom' => $vdep['nom_GB'],
                                'id_dept' => $vdep['id_dept'],
                            ];
                        }
                    }
                }
            }
        }

        dump($formatedCountry);

        return new Response('<html><body></body></html>');
    }
}
