<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Job;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repo = $entityManager->getRepository(Job::class);
        //dd($repo);
        $jobs = $repo->findAll();

        $jobTimes = [];
        foreach ($jobs as $job) {
            $jobTimes[$job->getId()] = $this->calculateTime($job->getCreatedAt());
        }

        return $this->render('home/index.html.twig', [
            'jobs' => $jobs,
            'jobTimes' => $jobTimes,
        ]);
    }

    private function calculateTime(\DateTimeImmutable $created): string
    {
        $currentTime = new \DateTimeImmutable();
        $timeDifference = $currentTime->getTimestamp() - $created->getTimestamp();

        $hoursAgo = floor($timeDifference / (60 * 60));
        $daysAgo = floor($timeDifference / (24 * 60 * 60));
        $monthsAgo = floor($daysAgo / 30);

        if ($monthsAgo == 1) {
            return "1 month ago";
        } elseif ($monthsAgo > 1) {
            return "$monthsAgo months ago";
        } elseif ($daysAgo == 1) {
            return "1 day ago";
        } elseif ($daysAgo > 1) {
            return "$daysAgo days ago";
        } elseif ($hoursAgo == 1) {
            return "1 hour ago";
        } else {
            return "$hoursAgo hours ago";
        }
    }

}
