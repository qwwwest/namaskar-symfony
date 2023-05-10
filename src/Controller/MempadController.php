<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RequestContext;
use App\Namaskar\Namaskar;
use Michelf\MarkdownExtra;



class MempadController extends AbstractController
{
    private $mempad;
    private $namaskar;

    #[Route('/{p1}/{p2}/{p3}/{p4}/{p5}/{p6}', name: 'app_mempad_index')]
    public function index(
        RequestContext $request_context,
        Namaskar $namaskar,
        $p1 = null,
        $p2 = null,
        $p3 = null,
        $p4 = null,
        $p5 = null,
        $p6 = null
    ): Response {
        $sitename = $GLOBALS['mempad'];
        $url = $GLOBALS['q'];
        $varFolder = $this->getParameter('kernel.project_dir') . "/var/sites";
        $this->mempad = $namaskar->getMempad($varFolder, $sitename);

        $id = $this->mempad->getElementByUrl($url)->id;
        $content = $this->mempad->getContentById($id);
        $html = MarkdownExtra::defaultTransform($content);
        $elt = $this->mempad->getElementById($id);
        $title = $elt->title;



        return $this->render('mempad/index.html.twig', [
            //return $this->render('bootstrap5/index.html.twig', [
            'content' => $html,
            'title' => $title,
            'footer' => 'FOOTER',
            'aside' => 'MENU LEFT',
            'elts' => $this->mempad->getRootElements(),
            'level' => 2,
            'parentActive' => true,
        ]);
    }

    public function renderSubmenu($menu, $level, $isDynamic = false)
    {

        if (!$menu || $level === 0) {
            return '';
        }

        $html = '';
        $url = $this->config->value('page.urlFound');
        foreach ($menu as $key => $item) {

            if (strpos($item->title, '.') === 0) {

                continue;
            }
            if (strpos($item->title, '!') === 0 && !$this->isAuthed) {

                continue;
            }


            $classes = [];
            $active = "";
            if ($isDynamic) {
                if (strpos($url, $item->url) === 0) {
                    $classes[] = 'dynamic';
                }
            }

            $url = $this->config->value('page.url');
            $url = $_SERVER['REQUEST_URI']; // not proud of this hack.
            if ($url === substr($_SERVER['PHP_SELF'], 0, -10) . '/' . $item->url) {
                $active = 'class="active"';
            }

            $rendered = $this->renderSubmenu($item->children, $level - 1, $isDynamic);
            if ($rendered && $isDynamic) {
                $classes[] = 'hasChildren';
            }

            $dyn = $classes ? ' class="' . implode(' ', $classes) . '"' : '';
            $html .= "<li$dyn><a href=\"$item->url\" $active> $item->title</a>"
                . $rendered . "</li>\n";
        }

        if ($isDynamic) {
            return "<ul class=\"dynamic\">$html</ul>";
        }

        return "<ul>$html</ul>";
    }

}