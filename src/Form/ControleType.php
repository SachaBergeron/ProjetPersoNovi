<?php

namespace App\Form;

use App\Entity\Controle;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ControleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $notes = $options['notes'];

        $builder
            ->add('nom')
            ->add('coefficient')
            ->add('date')
        ;

        foreach($notes as $note)
        {
            $builder
                ->add('note' . $note->getEtudiant()->getId(), FloatType::class, array('label' => $note->getEtudiant()->getPrenom() . ' ' . $note->getEtudiant()->getNom()))
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Controle::class,
        ]);
    }
}
