<?php

namespace App\Form;

use App\Entity\Controle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ControleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $etudiants = $options['etudiants'];

        $builder
            ->add('nom')
            ->add('coefficient')
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('noteMax')
        ;

        foreach($etudiants as $etudiant)
        {
            $builder
                ->add('note' . $etudiant->getId(), NumberType::class, array(
                    "mapped" => false,
                    'label' => 'note de ' . $etudiant->getPrenom() . ' ' . $etudiant->getNom() . ': '))
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Controle::class,
            'etudiants' => null,
        ]);
    }
}
