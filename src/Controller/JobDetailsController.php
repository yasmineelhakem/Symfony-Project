<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;

class JobDetailsController extends AbstractController
{
    #[Route('/job/{job}', name: 'job_details')]
    public function details(Job $job, EntityManagerInterface $entityManager): Response
    {
        //$job = $entityManager->getRepository(Job::class)->find($id);

        if (!$job) {
            throw $this->createNotFoundException('The job does not exist');
        }

        $jobTimes= $this->calculateTime($job->getCreatedAt());

        return $this->render('job_details/index.html.twig', [
            'job' => $job,
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
