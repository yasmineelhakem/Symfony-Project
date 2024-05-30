<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\User;
use App\Entity\Job;
use App\Entity\Application;
use DateTimeImmutable;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $user_type = ['Recruiter', 'Jobseeker', 'Admin'];
        //$jobs = ['Software Engineer', 'Marketing Manager', 'Graphic Designer', 'Project Manager'];
        $recruiters = [];
        $jobSeekers = [];

        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setName($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setEmail($faker->email);
            $user->setPassword($faker->password);
            $user->setUserType($faker->randomElement($user_type));
            /* $user->setJob($faker->randomElement($jobs));
             $user->setCity($faker->city);
             $user->setImageUrl($faker->imageUrl($width = 640, $height = 480));*/
            $userType = $user->getUserType();
            if ($userType == 'Recruiter') {
                $recruiters[] = $user;
                $user->addRole('ROLE_RECRUITER');
                $user->setCompany($faker->company);
            } else if ($userType == 'Jobseeker') {
                $jobSeekers[] = $user;
                $user->addRole('ROLE_JOBSEEKER');
                $user->setSkills($faker->text(50));
            } else {
                $user->addRole('ROLE_ADMIN');
            }

            /*if($user->getUserType() == 'recruiter'){
                $user->setPersonalInfo($faker->company);
            }

            else if($user->getUserType() == 'jobseeker'){
                $user->setPersonalInfo($faker->text([50]));
            }*/

            $manager->persist($user);
        }

        $jobs = [];
        for ($i = 0; $i < 7; $i++) {
            $job = new Job();
            $job->setPosition($faker->word());
            $job->setDescription($faker->text(250));
            $job->setEntreprise($faker->company());
            $job->setLocation($faker->city());
            $job->setEmploymentType($faker->word());

            $dateTimeImmutable = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2022-05-12 15:30:00');
            $job->setCreatedAt($dateTimeImmutable);

            if (!empty($recruiters)) {
                $randomRecruiter = $recruiters[array_rand($recruiters)];
                $job->setRecruiter($randomRecruiter);
            }
            $jobs[] = $job;
            $manager->persist($job);
        }

        for ($i = 0; $i < 7; $i++) {

            $application = new Application();

            if (!empty($jobSeekers)) {
                $randomJobSeeker = $jobSeekers[array_rand($jobSeekers)];
                $application->setJobseeker($randomJobSeeker);
            }

            if (!empty($jobs)) {
                $randomJob = $jobs[array_rand($jobs)];
                $application->setJob($randomJob);
            }

            $manager->persist($application);
        }

        $manager->flush();
    }
}
