<?php

namespace App\Controller\Back;

use App\Entity\Contract;
use App\Form\Back\ContractType;
use App\Repository\ClientRepository;
use App\Repository\ContractRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class HomeController
 * @package App\Controller\backOffice
 * @Route("/admin/contract", name="_admin_contract")
 */
class ContractController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var ContractRepository
     */
    private $contractRepository;

    /**
     * DomainController constructor.
     * @param EntityManagerInterface $manager
     * @param ContractRepository $contractRepository
     */
    public function __construct(EntityManagerInterface $manager, ContractRepository $contractRepository)
    {
        $this->manager = $manager;
        $this->contractRepository = $contractRepository;
    }

    /**
     * @Route("/index/{page}", name="_index", defaults={"page":1})
     * @param int $page
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(int $page, PaginatorInterface $paginator, Request $request)
    {
        $clientId = ((int)$request->query->get('client')) ?? null;
        $contracts = $paginator->paginate(
            $this->contractRepository->allQuery($clientId),
            $page
        );

        return $this->render('backOffice/pages/Contract/index.html.twig', [
            'contracts' => $contracts,
            'client' => $clientId
        ]);
    }

    /**
     * @Route("/edition/{id}", name="_edit")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit($id, Request $request)
    {
        $contract = $this->contractRepository->find($id);

        $form = $this->createForm(ContractType::class, $contract);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($contract);
            $this->manager->flush();
        }

        return $this->render('backOffice/pages/Contract/types/contractEdit.html.twig', [
            'c' => $contract,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/nouveau/{idClient}", name="_new", defaults={"idClient":null})
     * @param Request $request
     * @param $idClient
     * @param ClientRepository $clientRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, $idClient, ClientRepository $clientRepository)
    {
        $contract = new Contract();

        if($idClient !== null){
            $client = $clientRepository->find($idClient);
            $contract->setClient($client);
        }

        $form = $this->createForm(ContractType::class, $contract);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($contract);
            $this->manager->flush();

            return $this->redirectToRoute('_admin_contract_edit', ['id' => $contract->getId()]);
        }

        return $this->render('backOffice/pages/Contract/types/contractEdit.html.twig', [
            'c' => $contract,
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
        $contract = $this->contractRepository->find($id);
        $this->addFlash('danger', 'Le contract ' . $contract->getEmail() . ' a été supprimé');
        $this->manager->remove($contract);
        $this->manager->flush();
        return $this->redirectToRoute('_admin_contract_index');
    }
}
