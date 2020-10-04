<?php


namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, ['label'=>'Sortie\s titre'])
            ->add('infosSortie', TextareaType::class, ['label'=>'Serie\s infosSortie','attr' => ['class' => 'infosSortie-text']])

            ->add('nbInscriptionsMax', IntegerType::class, ['attr' => ['placeholder' => 333]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}