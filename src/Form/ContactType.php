<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, options:['label' => 'Email', 'attr' => ['class' => 'form-control'], 'label_attr' => ['class' => 'form-label']])
            ->add('firstname', TextType::class, options: ['label' => 'Votre prénom', 'attr' => ['class' => 'form-control'], 'label_attr' => ['class' => 'form-label']])
            ->add('lastname', TextType::class, options: ['label' => 'Votre nom',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label']])
            ->add('message', TextareaType::class, options: ['label' => 'Message',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label']])
            ->add('Envoyer', SubmitType::class, options: ['attr' => ['class' => 'btn btn-success mt-3'],
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
