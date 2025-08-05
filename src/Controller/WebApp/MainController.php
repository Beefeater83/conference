<?php

declare(strict_types=1);

namespace App\Controller\WebApp;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    public function __construct() {}

    #[Route('/{_locale}', name: 'main_page', requirements: ['_locale' => 'en|ru'], defaults: ['_locale' => 'en'], methods: ['GET'])]
    public function mainPage(Request $request): Response
    {
    //    $conference = 'Grain 2025';

        return $this->render('components/main_page.html.twig', [
           // 'data' => $conference
        ]);
    }

    #[Route('/{_locale}/about', name: 'about_conference', requirements: ['_locale' => 'en|ru'], defaults: ['_locale' => 'en'], methods: ['GET'])]
    public function aboutConference(Request $request): Response
    {

        return $this->render('components/about.html.twig', []);
    }
}
