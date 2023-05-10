<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Namaskar\Namaskar;
class MainController extends AbstractController
{
    private $mempad;
    private $namaskar;

    #[Route('/toto/{p1}/{p2}/{p3}/{p4}/{p5}/{p6}', name: 'app_index')]
    public function index(Namaskar $namaskar,$p1 = null, $p2 = null,$p3 = null, $p4 = null,$p5 = null, $p6 = null): Response
    {
        $sitename = $GLOBALS['mempad'];
        $url = $GLOBALS['q'];
        $varFolder = $this->getParameter('kernel.project_dir')."/var/sites";
        $this->mempad = $namaskar->getMempad($varFolder, $sitename); 

        $id = $this->mempad->getElementByUrl('/')->id ;
        $content = $this->mempad->getContentById($id);

        return $this->render('main/index.html.twig', [
            //  'controller_name' => "path= $p1 / $p2",
            'controller_name' => $content,
            'woot' => $GLOBALS['woot'],
        ]);
    }

    #[Route('/main', name: 'app_main')]
    public function app_main(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => "app_main",
            'woot' => $GLOBALS['woot'],
        ]);
    }

}