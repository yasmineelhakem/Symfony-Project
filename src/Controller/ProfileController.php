<?php

namespace App\Controller;

use AllowDynamicProperties;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\editProfileType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AllowDynamicProperties] class ProfileController extends AbstractController
{
    private $security;

    public function __construct(Security $security,ParameterBagInterface $parameters)
    {
        $this->security = $security;
        $this->parameters = $parameters;
    }
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/editProfile', name: 'app_editProfile')]
    public function edit(Request $request,SluggerInterface $slugger,EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(editProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setName($form->get('name')->getData());
            $user->setLastName($form->get('last_name')->getData());
            $user->setEmail($form->get('email')->getData());
            if ($this->security->isGranted('ROLE_JOBSEEKER')) {
                $user->setSkills($form->get('skills')->getData());
            }
            if ($this->security->isGranted('ROLE_RECRUITER')) {
                $user->setCompany($form->get('company')->getData());
            }

            $picture = $form->get('profilePic')->getData();

            if ($picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$picture->guessExtension();

                $picturesDirectory = $this->getParameter('pictures_directory');

                try {
                    $picture->move($picturesDirectory, $newFilename);
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }
                $user->setProfilePic($newFilename);
            }
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('profile/editProfile.html.twig', [
            'form' => $form,
        ]);
    }
}
