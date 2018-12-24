<?php

namespace App\Controller;

use App\Services\ExtractDb;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CachingController extends AbstractController
{

    /**
     * @Route("/caching", name="caching")
     * @param Request $request
     * @return Response
     */
    public function caching(Request $request): Response
    {

        if ($request->isXmlHttpRequest() && (int)$request->get('i') >= 0) {
            $output = shell_exec('cd '.$this->getParameter('kernel.project_dir').' && php bin/console CODB ' . (int)$request->get('i'));
            return new JsonResponse(['data' => (int)$request->get('i')]);
        } elseif ($request->get('t') === 'reload') {
            $output = shell_exec('cd '.$this->getParameter('kernel.project_dir').' && php bin/console doctrine:database:drop --force && php bin/console doctrine:database:create && php bin/console doctrine:schema:update --force && php bin/console CODB 1000');
            $this->redirectToRoute('caching',[
                'output' => $output
            ]);
        }
        return new Response($this->renderView('prod/utils.html.twig'));
    }
}