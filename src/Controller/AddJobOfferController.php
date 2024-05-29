<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Job;
use App\Form\JobType;

class AddJobOfferController extends AbstractController
{
    #[Route('/addJobOffer', name: 'app_add_job_offer')]
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        $entityManager->persist($job);
        $entityManager->flush();

        return $this->render('add_job_offer/index.html.twig',  [
            'JobType' => $form,
        ]);
    }
}