<?php

namespace App\Controller\Back;

use App\Entity\Activity;
use App\Form\Back\ActivityEditType;
use App\Repository\ActivityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller\backOffice
 * @Route("/admin/activites", name="_admin_activities")
 */
class ActivityController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var ActivityRepository
     */
    private $activityRepository;

    public function __construct(ActivityRepository $activityRepository, ObjectManager $manager)
    {
        $this->activityRepository = $activityRepository;
        $this->manager = $manager;
    }

    /**
     * @Route("/index/{id}", name="_index", defaults={"id":null})
     * @param $id
     * @return Response
     */
    public function index($id)
    {
        if ($id === null) {
            $activities = $this->activityRepository->findBy(['level' => 0]);
        } else {
            $activities = $this->activityRepository->find($id);
            $activities = $activities->getChildren();
        }
        return $this->render('backOffice/pages/activity/index.html.twig', [
            'activities' => $activities
        ]);
    }

    /**
     * @Route("/edition/{id}", name="_edit")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit($id, Request $request)
    {
        $activity = $this->activityRepository->find($id);

        $form = $this->createForm(ActivityEditType::class, $activity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($activity);
            $this->manager->flush();

            return $this->redirectToRoute('_admin_activities_edit', ['id' => $activity->getId()]);
        }

        return $this->render('backOffice/pages/activity/types/activityEdit.html.twig', [
            'a' => $activity,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/nouveau", name="_new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request)
    {
        $activity = new Activity();

        $form = $this->createForm(ActivityEditType::class, $activity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($activity);
            $this->manager->flush();

            return $this->redirectToRoute('_admin_activities_edit', ['id' => $activity->getId()]);
        }

        return $this->render('backOffice/pages/activity/types/activityNew.html.twig', [
            'a' => $activity,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete($id)
    {
        $activity = $this->activityRepository->find($id);
        $this->manager->remove($activity);
        $this->manager->flush();
        $this->addFlash('success', 'l\'activité <strong>'.$activity->__toString().' </strong>a été supprimée');
        return $this->redirectToRoute('_admin_activities_index');
    }
}
