<?php

namespace App\Controller;

use AllowDynamicProperties;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ApplicationType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Entity\Job;
use App\Entity\Application;

#[AllowDynamicProperties] class ApplyController extends AbstractController
{
    private $security;

    public function __construct(Security $security,ParameterBagInterface $parameters)
    {
        $this->security = $security;
        $this->parameters = $parameters;
    }

    #[Route('/apply/{job}', name: 'app_apply')]
    public function index(Job $job,Request $request,SluggerInterface $slugger,EntityManagerInterface $entityManager): Response
    {

        if (!$job) {
            throw $this->createNotFoundException('The job does not exist');
        }

        $user=$this->security->getUser();
        $application=new Application();

        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $application->setMotivation($form->get('motivation')->getData());
            $cv = $form->get('cv')->getData();

            if ($cv) {
                $originalFilename = pathinfo($cv->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$cv->guessExtension();

                $cvsDirectory = $this->getParameter('cv_directory');

                try {
                    $cv->move($cvsDirectory, $newFilename);
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }
                $application->setCv($newFilename);
            }

            $job->addApplication($application);
            $user->addApplication($application);


            $entityManager->persist($application);
            $entityManager->flush();
            $this->addFlash('success', 'Application submitted successfully.');

        }

        return $this->render('apply/index.html.twig', [
            'form' => $form,

        ]);
    }
}
