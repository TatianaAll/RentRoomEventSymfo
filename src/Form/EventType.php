<?php

namespace App\Form;

use App\Entity\Animator;
use App\Entity\Category;
use App\Entity\Event;
use App\Entity\Room;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, options:
                ['label' => 'Titre de l\'évènement',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('description', TextareaType::class, options:
                ['label' => 'Description',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('start_time', DateTimeType::class, options: [
                'label' => 'Début de l\'évènement',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'widget' => 'single_text',
            ])
            ->add('end_time', DateTimeType::class, options: [
                'label' => 'Fin de l\'évènement',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'widget' => 'single_text',
            ])
            ->add('animator', EntityType::class, [
                'class' => Animator::class,
                'choice_label' => 'fullname',
                'multiple' => false,
                'attr' => ['class' => 'form-select'],
                'label' => 'animateur de l\'évènement',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('room', EntityType::class, [
                'class' => Room::class,
                'choice_label' => 'name',
                'multiple' => false,
                'attr' => ['class' => 'form-select'],
                'label' => 'la salle',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => false,
                'attr' => ['class' => 'form-select'],
                'label' => 'genre d\'évènement',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('Enregistrer', SubmitType::class, options :
                ['attr' => ['class' => 'btn btn-success mt-3'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
