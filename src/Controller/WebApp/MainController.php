<?php

declare(strict_types=1);

namespace App\Controller\WebApp;

use App\Service\CalculationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    private CalculationService $calculationService;
    public function __construct(CalculationService $calculationService) {
        $this->calculationService = $calculationService;
    }

    #[Route('/{_locale}', name: 'main_page', requirements: ['_locale' => 'en|ru'], defaults: ['_locale' => 'en'], methods: ['GET'])]
    public function mainPage(Request $request): Response
    {
        $timeUntilConference = $this->calculationService->getTimeUntilConference();

        return $this->render('components/main_page.html.twig', [
            'data' => $timeUntilConference
        ]);
    }

    #[Route('/{_locale}/about', name: 'about_conference', requirements: ['_locale' => 'en|ru'], defaults: ['_locale' => 'en'], methods: ['GET'])]
    public function aboutConference(Request $request): Response
    {

        return $this->render('components/about.html.twig', []);
    }
}
