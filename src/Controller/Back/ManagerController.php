<?php

namespace App\Controller\Back;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * Class ManagerController
 * @package App\Controller\Back
 *
 * @Route("/admin/manager", name="_manager")
 */
class ManagerController extends AbstractController
{
    /**
     * @Route("/index", name="_index")
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
        shell_exec('cd ' . $this->getParameter('kernel.project_dir') . ' && php bin/console clear:cache');
        $this->addFlash('success', 'Cache réinitialisé');
        return $this->redirectToRoute('_manager_index');
    }
}