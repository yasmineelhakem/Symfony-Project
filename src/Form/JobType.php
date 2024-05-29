<?php

namespace App\Form;

use App\Entity\Job;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('position')
            ->add('description')0
            ->add('entreprise')
            ->add('location')

            ->add('employment_type', ChoiceType::class, [
                'choices'  => [
                    'Option 1' => 'Fulltime',
                    'Option 2' => 'PartTime',
                    'Option 3' => 'Internship',
                ],
                'label' => 'Select an Employment type',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }
}
