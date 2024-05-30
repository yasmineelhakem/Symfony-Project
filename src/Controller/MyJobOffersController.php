<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MyJobOffersController extends AbstractController
{
    #[Route('/myJobOffers', name: 'app_my_job_offers')]
    public function index(): Response
    {

        $user=$this->getUser();

        if (!$user || !$this->isGranted('ROLE_RECRUITER')) {
            throw $this->createAccessDeniedException('You do not have access to this page.');
        }

        $jobs = $user->getJobs();

        return $this->render('my_job_offers/index.html.twig', [
            'jobs' => $jobs,
        ]);
    }
}
