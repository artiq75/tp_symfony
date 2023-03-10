<?php

namespace App\Form;

use App\Entity\Property;
use App\Entity\PropertyType as Type;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PropertyType extends AbstractType
{
    public function __construct(
        private Security $security
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('availability_start', DateType::class, [
            'widget' => 'single_text'
        ])
        ->add('availability_end', DateType::class, [
            'widget' => 'single_text'
        ]);

        if ($this->security->isGranted('ROLE_ADMIN')) {

            $builder
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                    ->where('p.id NOT IN(:ids)')
                    ->setParameter('ids', [Type::POOL_TYPE, Type::STAY_TYPE]);
                },
                'choice_label' => 'label'
            ])
            ->add('image_file', VichImageType::class, [
                'required' => true,
                'allow_delete' => false,
                'download_uri' => false,
            ]);

        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
            'translation_domain' => 'property_form',
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
