<?php

namespace App\Controller\Back;


use App\Services\XmlService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * Class ManagerController
 * @package App\Controller\Back
 */
class ManagerController extends AbstractController
{
    private $xml;

    public function __construct(XmlService $xmlService)
    {
        $this->xml = $xmlService;
    }

    /**
     * @Route("/admin/manager/index", name="_manager_index")
     * @throws \Exception
     */
    public function index()
    {
        return $this->render('backOffice/pages/tools/index.html.twig');
    }

    /**
     * @Route("/d584ad4af7a5v8va7va8897ad78ada825ad5ad8a8/cache", name="_manager_cache")
     */
    public function cache()
    {
        $res = shell_exec('php ' . $this->getParameter('kernel.project_dir') . '/bin/console cache:clear');
        $this->addFlash('success', $res);
        return $this->redirectToRoute('_manager_index');
    }

    /**
     * @Route("/d584ad4af7a5v8va7va8897ad78ada825ad5ad8a8/xml", name="_manager_xml")
     */
    public function xml()
    {
        $this->xml->run();
        $this->addFlash('success', 'xml réinitialisés');
        return $this->redirectToRoute('_manager_index');
    }
}