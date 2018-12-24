<?php

namespace App\Controller\Back;


use App\Entity\Options;
use App\Form\Back\OptionsType;
use App\Repository\OptionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin", name="_options")
 */
class OptionController extends AbstractController
{
    /**
     * @var OptionsRepository
     */
    private $optionsRepository;

    public function __construct(OptionsRepository $optionsRepository)
    {
        $this->optionsRepository = $optionsRepository;
    }

    /**
     * @Route("/options/{page}", name="_index", defaults={"page":1})
     * @param PaginatorInterface $paginator
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(PaginatorInterface $paginator, $page)
    {
        return $this->render('backOffice/pages/options/index.html.twig', [
            'options' => $paginator->paginate($this->optionsRepository->getQueryForAll(), $page)
        ]);
    }

    /**
     * @Route("/option/nouveau", name="_new")
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(EntityManagerInterface $manager, Request $request)
    {
        $option = new Options();

        $form = $this->createForm(OptionsType::class, $option);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($option);
            $manager->flush();
            return $this->redirectToRoute('_options_edit', ['id' => $option->getId()]);
        }

        return $this->render('backOffice/pages/options/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/option/{id}", name="_edit", defaults={"id":1})
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(EntityManagerInterface $manager, Request $request, $id)
    {
        $options = $this->optionsRepository->find($id);

        $form = $this->createForm(OptionsType::class, $options);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($options);
            $manager->flush();
        }


        return $this->render('backOffice/pages/options/edit.html.twig', [
            'option' => $options,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/option/delete/{id}", name="_delete")
     * @param EntityManagerInterface $manager
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(EntityManagerInterface $manager, $id)
    {
        $manager->remove($this->optionsRepository->find($id));
        $manager->flush();
        return $this->redirectToRoute('_options_index');
    }
}