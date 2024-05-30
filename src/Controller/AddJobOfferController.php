<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\Job;
use App\Form\JobType;

class AddJobOfferController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/addJobOffer', name: 'app_add_job_offer')]
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $recruiter = $this->security->getUser();
            $job->setRecruiter($recruiter);

            $entityManager->persist($job);
            $entityManager->flush();
        }

        return $this->render('add_job_offer/index.html.twig',  [
            'job' => $form,
        ]);
    }
}