<?php

namespace App\Controller;

use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

class EssaisController extends AbstractController
{
    /**
     * @Route("/", name="test")
     * @param ClientRepository $clientRepository
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ClientRepository $clientRepository, Request $request, EntityManagerInterface $manager)
    {
//        $client = new Client();
        $client = $clientRepository->find(1);
        $form = $this->createForm(ClientType::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($client);
            $manager->flush();
        }

        return $this->render('test.html.twig', [
            'clients' => $clientRepository->findAll(),
            'form' => $form->createView(),
            'structureActivities' => Yaml::parseFile($this->container->get('parameter_bag')->get('kernel.project_dir') . '/src/Data/activities.yaml'),
            'structureServedZone' => Yaml::parseFile($this->container->get('parameter_bag')->get('kernel.project_dir') . '/src/Data/servedZone.yaml')
        ]);
    }

    /**
     * @Route("/dumpDB", name="dumpDB")
     * @return Response
     */
    public function dumpDB()
    {
        $est = Yaml::parseFile(realpath('../src/Data/servedZone.yaml'));
        dump($est);
        return new Response($this->renderView('<html><body></body></html>'));
    }

}
