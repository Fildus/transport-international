<?php

namespace App\Controller\Back;

use App\Entity\Domain;
use App\Form\DomainType;
use App\Repository\DomainRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class HomeController
 * @package App\Controller\backOffice
 * @Route("/admin/domaines", name="_admin_domain")
 */
class DomainController extends AbstractController
{
    /**
     * @var DomainRepository
     */
    private $domainRepository;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * DomainController constructor.
     *
     * @param DomainRepository       $domainRepository
     * @param EntityManagerInterface $manager
     */
    public function __construct(DomainRepository $domainRepository, EntityManagerInterface $manager)
    {
        $this->domainRepository = $domainRepository;
        $this->manager = $manager;
    }

    /**
     * @Route("/index/{page}", name="_index", defaults={"page":1})
     * @param int                $page
     * @param PaginatorInterface $paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(int $page, PaginatorInterface $paginator)
    {
        $domains = $paginator->paginate(
            $this->domainRepository->allQuery(),
            $page
        );

        return $this->render('backOffice/pages/Domains/index.html.twig', [
            'domains' => $domains
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
        $domain = $this->domainRepository->find($id);

        $form = $this->createForm(DomainType::class, $domain);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($domain);
            $this->manager->flush();
        }

        return $this->render('backOffice/pages/Domains/types/domainEdit.html.twig', [
            'd' => $domain,
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
        $domain = new Domain();

        $form = $this->createForm(DomainType::class, $domain);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($domain);
            $this->manager->flush();

            return $this->redirectToRoute('_admin_domain_edit', ['id' => $domain->getId()]);
        }

        return $this->render('backOffice/pages/Domains/types/domainEdit.html.twig', [
            'd' => $domain,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete")
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete($id)
    {
        $domain = $this->domainRepository->find($id);
        $this->addFlash('danger', 'Le domaine ' . $domain->getTitle() . ' a été supprimé');
        $this->manager->remove($domain);
        $this->manager->flush();
        return $this->redirectToRoute('_admin_domain_index');
    }
}
