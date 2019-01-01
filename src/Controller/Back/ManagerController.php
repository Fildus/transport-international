<?php

namespace App\Controller\Back;


use App\Services\XmlService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * Class ManagerController
 * @package App\Controller\Back
 *
 * @Route("/admin/manager", name="_manager")
 */
class ManagerController extends AbstractController
{
    private $xml;

    public function __construct(XmlService $xmlService)
    {
        $this->xml = $xmlService;
    }

    /**
     * @Route("/index", name="_index")
     * @throws \Exception
     */
    public function index()
    {
        return $this->render('backOffice/pages/tools/index.html.twig');
    }

    /**
     * @Route("/cache", name="_cache")
     */
    public function cache()
    {
        shell_exec('php ' . $this->getParameter('kernel.project_dir') . '/bin/console cache:clear');
        $this->addFlash('success', 'Cache réinitialisé');
        return $this->redirectToRoute('_manager_index');
    }

    /**
     * @Route("/xml", name="_xml")
     */
    public function xml()
    {
        $this->xml->run();
        $this->addFlash('success', 'xml réinitialisés');
        return $this->redirectToRoute('_manager_index');
    }
}