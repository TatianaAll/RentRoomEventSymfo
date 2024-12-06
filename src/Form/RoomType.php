<?php

namespace App\Form;

use App\Entity\Etablishment;
use App\Entity\Tag;
use App\Entity\Room;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', type: TextType::class, options: [
                'label' => 'Nom de la salle',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('capacity', type: NumberType::class, options: [
                'label' => 'Capacité de la salle',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label']
            ])

            ->add('etablishment', EntityType::class, [
                'class' => Etablishment::class,
                'choice_label' => 'name',
                'multiple' => false,
                'attr' => ['class' => 'form-select'],
                'label' => 'Etablissement propriétaire',
                'label_attr' => ['class' => 'form-label'],
            ])

            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
                'attr' => ['class' => 'form-select'],
                'label' => 'Tags',
                'label_attr' => ['class' => 'form-label'],
            ])

            ->add('Enregistrer', SubmitType::class, options : [
                'attr' => ['class' => 'btn btn-success mt-3'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
