<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Job;
use App\Entity\Application;

class ApplicationsController extends AbstractController
{
    #[Route('/applications/{job}', name: 'app_applications')]
    public function index(Job $job): Response
    {
        $applications = $job->getApplications();
        /*foreach ($applications as $application) {
            $user=$application->getJobseeker();
        }*/
        return $this->render('applications/index.html.twig', [
            'applications' => $applications,
        ]);
    }
}
