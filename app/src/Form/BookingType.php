<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /**
         * @var Property
         */
        $property = $options['data']->getProperty();

        $builder
            ->add('customer_firstname')
            ->add('customer_lastname')
            ->add('customer_address')
            ->add('children')
            ->add('adults')
            ->add('start_date', DateType::class, [
                'widget' => 'single_text',
                'data' => $property->getAvailabilityStart()
            ])
            ->add('end_date', DateType::class, [
                'widget' => 'single_text',
                'data' => $property->getAvailabilityEnd()
            ])
            ->add('pool_acess')
            ->add('grant_access')
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary w-100',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'translation_domain' => 'booking_form',
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
