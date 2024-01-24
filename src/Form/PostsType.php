<?php

namespace App\Form;

use App\Entity\Posts;
use App\Entity\Tags;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PostsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du post',
                'required' => true,
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'required' => true,
                'attr' => [
                    'class' => 'tinymce',
                ],
            ])
            ->add('picture', FileType::class, [
                'data_class' => null,
                'label' => 'Image',
                'required' => false,
                'help' => 'Fichier jpg, jpeg, png, webp ne dépassant pas 1Mo',
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'maxSizeMessage' => 'Votre fichier ne doit pas dépasser les 1 Mo',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Merci de télécharger une image valide.',
                    ]),
                ]
            ])
            ->add('date_add', DateType::class, [
                'label' => 'date de publication',
                'widget' => 'single_text',
                'data' => $options['date'],
                'help' => 'Saisir une date future'
            ])
            ->add('fk_tags', EntityType::class, [
                'class' => Tags::class,
                'choice_label' => 'title',
                'multiple' => true,
                'label' => 'Tags',
                'expanded' => true, // Met les tags dans une checkbox au lieu d'une liste.
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Posts::class,
            'date' => null,
        ]);
    }
}
