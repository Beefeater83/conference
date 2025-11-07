<?php

declare(strict_types=1);

namespace App\Controller\WebApp;

use App\Service\CalculationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;

class MainController extends AbstractController
{
    private CalculationService $calculationService;
    public function __construct(CalculationService $calculationService) {
        $this->calculationService = $calculationService;
    }

    #[Route('/{_locale}', name: 'main_page', requirements: ['_locale' => 'en|ru|ua'], defaults: ['_locale' => 'en'], methods: ['GET'])]
    public function mainPage(Request $request): Response
    {
        $timeUntilConference = $this->calculationService->getTimeUntilConference();

        return $this->render('components/main_page.html.twig', [
            'data' => $timeUntilConference
        ]);
    }

    #[Route('/{_locale}/about', name: 'about_conference', requirements: ['_locale' => 'en|ru|ua'], defaults: ['_locale' => 'en'], methods: ['GET'])]
    public function aboutConference(Request $request): Response
    {

        return $this->render('components/about.html.twig', []);
    }

    #[Route('/{_locale}/contacts', name: 'contacts', requirements: ['_locale' => 'en|ru|ua'], defaults: ['_locale' => 'en'], methods: ['GET'])]
    public function contacts(Request $request): Response
    {

        return $this->render('components/contacts.html.twig', []);
    }

    #[Route('/{_locale}/registration', name: 'registration', requirements: ['_locale' => 'en|ru|ua'], defaults: ['_locale' => 'en'], methods: ['GET'])]
    public function registration(Request $request): Response
    {

        return $this->render('components/registration.html.twig', []);
    }

    #[Route('/{_locale}/program', name: 'program', requirements: ['_locale' => 'en|ru|ua'], defaults: ['_locale' => 'en'], methods: ['GET'])]
    public function program(Request $request): Response
    {

        return $this->render('components/program.html.twig', []);
    }

    #[Route('/{_locale}/hotel', name: 'hotel', requirements: ['_locale' => 'en|ru|ua'], defaults: ['_locale' => 'en'], methods: ['GET'])]
    public function hotel(Request $request): Response
    {

        return $this->render('components/hotel.html.twig', []);
    }

    #[Route('/{_locale}/participation', name: 'participation', requirements: ['_locale' => 'en|ru|ua'], defaults: ['_locale' => 'en'], methods: ['GET'])]
    public function participation(Request $request): Response
    {

        return $this->render('components/participation.html.twig', []);
    }

    #[Route('/{_locale}/speakers', name: 'speakers', requirements: ['_locale' => 'en|ru|ua'], defaults: ['_locale' => 'en'], methods: ['GET'])]
    public function speakers(Request $request): Response
    {

        return $this->render('components/speakers.html.twig', []);
    }

    #[Route('/{_locale}/partners', name: 'partners', requirements: ['_locale' => 'en|ru|ua'], defaults: ['_locale' => 'en'], methods: ['GET'])]
    public function partners(Request $request): Response
    {

        return $this->render('components/partners.html.twig', []);
    }

    #[Route('/download/sponsor-options', name: 'download_sponsor_options')]
    public function downloadSponsorOptions(): BinaryFileResponse
    {
        $filePath = $this->getParameter('kernel.project_dir') . '/public/pdf/sponsor_options.pdf';

        $response = new BinaryFileResponse($filePath);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'AgroFoodSummit_sponsorship_advertising.pdf'
        );

        return $response;
    }

    #[Route('/{_locale}/admin', name: 'admin', requirements: ['_locale' => 'en|ru|ua'], defaults: ['_locale' => 'en'], methods: ['GET'])]
    public function admin(Request $request, ClientRegistry $clientRegistry): Response
    {
        $allowedEmails = explode(',', $_ENV['ALLOWED_EMAILS'] ?? '');
        $session = $request->getSession();
        $userEmail = $session->get('oauth_user');

        if (!$userEmail) {
            $client = $clientRegistry->getClient('google');
            return $client->redirect([], []);
        }

        if (!in_array($userEmail, $allowedEmails)) {
            throw $this->createAccessDeniedException('Access denied');
        }

        return $this->render('components/admin.html.twig', []);
    }

    #[Route('/{_locale}/admin/google-check', name: 'admin_google_check')]
    public function googleCheck(Request $request, ClientRegistry $clientRegistry): Response
    {
        $client = $clientRegistry->getClient('google');
        $googleUser = $client->fetchUser();

        $allowedEmails = explode(',', $_ENV['ALLOWED_EMAILS'] ?? '');
        if (!in_array($googleUser->getEmail(), $allowedEmails)) {
            throw $this->createAccessDeniedException('Access denied');
        }

        $request->getSession()->set('oauth_user', $googleUser->getEmail());

        return $this->redirectToRoute('admin', ['_locale' => $request->getLocale()]);
    }

    #[Route('/{_locale}/admin/upload', name: 'admin_upload', requirements: ['_locale' => 'en|ru|ua'], defaults: ['_locale' => 'en'], methods: ['POST'])]
    public function upload(Request $request): Response
    {
        $file = $request->files->get('program_file');

        if ($file && $file->getMimeType() === 'image/png') {
            $targetDir = $this->getParameter('kernel.project_dir') . '/public/program';
            $file->move($targetDir, 'program1.png');

            $this->addFlash('success', 'Файл успешно загружен!');
        } else {
            $this->addFlash('error', 'Загрузи валидный PNG файл.');
        }

        return $this->redirectToRoute('admin', ['_locale' => $request->getLocale()]);
    }

    #[Route('/{_locale}/participants-list', name: 'participants', requirements: ['_locale' => 'en|ru|ua'], defaults: ['_locale' => 'en'], methods: ['GET'])]
    public function participants(Request $request): Response
    {

        return $this->render('components/participants.html.twig', []);
    }
}
