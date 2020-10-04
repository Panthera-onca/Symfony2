<?php


namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\SubmitButton;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('titre')
            ->add('dateHeureDebut')
            ->add('duree')
            ->add('dateLimiteInscription')
            ->add('infosSortie')
            ->add('campus')
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
        $formModifier = function (FormInterface $form, Ville $ville = null) {
            $lieux = null === $ville ? [] : $ville->getLieux();
            $form->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'placeholder' => ' -- Choisir un lieu -- ',
                'choices' => $lieux
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $ville = ($data->getLieu() !== null)?$data->getLieu()->getVille():null;
                $formModifier($event->getForm(), $ville);
            }
        );

        $builder->get('ville')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $ville = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $ville);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'validation_groups' => function(FormInterface $form) {
                if ($form->get('saveWithLieu')->isClicked()) {
                    return ["saveWithLieu"];
                }
                return ['save'];
            }
        ]);
    }
}