<?php

namespace App\Form;

use App\Entity\Prof;
use App\Entity\Module;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $profs = $options['profs'];
        $builder
            ->add('nom')
            ->add('vol_horaireTD',NumberType::class, ['label' => 'Volume Horaire TD'])
            ->add('vol_horaireTP',NumberType::class, ['label' => 'Volume Horaire TP'])
            ->add('vol_horaireCM',NumberType::class, ['label' => 'Volume Horaire CM'])
            ->add('coefficient')
            ->add('enseignant', EntityType::class, [
                'class' => Prof::class,
                'choice_label' => 'username',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Module::class,
        ]);
    }
}
