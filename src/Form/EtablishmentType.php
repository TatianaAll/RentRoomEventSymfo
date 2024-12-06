<?php

namespace App\Form;

use App\Entity\Etablishment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtablishmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, options:
                ['label' => 'Nom de l\'établissement',
                    'attr' => ['class' => 'form-control'],
                    'label_attr' => ['class' => 'form-label']
                ])
            ->add('address', TextType::class, options:
                ['label' => 'Adresse',
                    'attr' => ['class' => 'form-control'],
                    'label_attr' => ['class' => 'form-label']
                ])
            ->add('description', TextareaType::class, options:
                ['label' => 'Description de l\'établissement',
                    'attr' => ['class' => 'form-control'],
                    'label_attr' => ['class' => 'form-label']
                ])
            ->add('Enregistrer', SubmitType::class, options:
                ['attr' => ['class' => 'btn btn-success mt-3'],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etablishment::class,
        ]);
    }
}
