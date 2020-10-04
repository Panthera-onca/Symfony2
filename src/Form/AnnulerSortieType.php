<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnulerSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
             $builder
                 ->add('titre')
                 ->add('ville', EntityType::class, [
                     'mapped' => false,
                     'class' => 'App\Entity\Ville',
                     'placeholder' => ' -- Choisir une ville -- '
                 ])
                 ->add('ajouterLieu', SubmitType::class)
                 // https://symfony.com/doc/current/form/embedded.html#embedding-a-single-object
                 ->add('newLieu', LieuFormType::class, [
                     'label' => 'Lieu'
                 ])
                 ->add('saveWithLieu', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
