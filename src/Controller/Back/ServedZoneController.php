<?php

namespace App\Controller\Back;

use App\Entity\Location;
use App\Entity\ServedZone;
use App\Form\Back\ServedZoneEditType;
use App\Repository\LocationRepository;
use App\Repository\ServedZoneRepository;
use App\Services\Locale;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller\backOffice
 * @Route("/admin/zones-desservies", name="_admin_served_zone")
 */
class ServedZoneController extends AbstractController
{
    /**
     * @var ServedZoneRepository
     */
    private $servedZoneRepository;
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * ServedZoneController constructor.
     * @param ServedZoneRepository $servedZoneRepository
     * @param ObjectManager $manager
     * @param Locale $locale
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct(ServedZoneRepository $servedZoneRepository, ObjectManager $manager, Locale $locale)
    {
        $locale->setLocale();
        $this->servedZoneRepository = $servedZoneRepository;
        $this->manager = $manager;
    }

    /**
     * @Route("/index/{id}", name="_index", defaults={"id":null})
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index($id)
    {
        if ($id === null) {
            $servedZones = $this->servedZoneRepository->findBy(['level' => 0]);
        } else {
            $servedZones = $this->servedZoneRepository->find($id);
            $servedZones = $servedZones->getChildren();
        }
        return $this->render('backOffice/pages/ServedZone/index.html.twig', [
            'servedZones' => $servedZones
        ]);
    }

    /**
     * @Route("/edition/{id}", name="_edit")
     * @param         $id
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit($id, Request $request)
    {
        $servedZone = $this->servedZoneRepository->find($id);

        $form = $this->createForm(ServedZoneEditType::class, $servedZone);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($servedZone);
            $this->manager->flush();

            return $this->redirectToRoute('_admin_served_zone_edit', ['id' => $servedZone->getId()]);
        }

        return $this->render('backOffice/pages/ServedZone/types/servedZoneEdit.html.twig', [
            's' => $servedZone,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/nouveau", name="_new")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $servedZone = new ServedZone();

        $form = $this->createForm(ServedZoneEditType::class, $servedZone);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($servedZone);
            $this->manager->flush();

            return $this->redirectToRoute('_admin_served_zone_edit', ['id' => $servedZone->getId()]);
        }

        return $this->render('backOffice/pages/ServedZone/types/servedZoneNew.html.twig', [
            's' => $servedZone,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete")
     * @param                    $id
     * @param LocationRepository $locationRepository
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function delete($id, LocationRepository $locationRepository)
    {
        $servedZone = $this->servedZoneRepository->find($id);
        $servedZones = $this->servedZoneRepository->allChildrens($servedZone->getId());
        foreach ($servedZones as $child) {
            $locations = $locationRepository->findLocationByServedZone($child);
            foreach ($locations as $location) {
                /** @var $location Location */
                $location->removeLocation();
            }
        }
        $this->manager->remove($servedZone);
        $this->manager->flush();
        return $this->redirectToRoute('_admin_served_zone_index');
    }
}
