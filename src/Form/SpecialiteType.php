<?php

namespace App\Form;

use App\Entity\Specialite;
use App\Entity\Region;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecialiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class, [
                'required' => true,
            ])
            ->add('image', HiddenType::class, [
                'required' => true,
            ])
            ->add('attachment', FileType::class, [
                'required' => false,
                'mapped' => false,
            ])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'libelle',
                'multiple' => false,
                'expanded' => false,
                'required' => true,
            ])
            ->add('tag', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'libelle',
                'multiple' => true,
                'expanded' => true,
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Specialite::class,
        ]);
    }
}
