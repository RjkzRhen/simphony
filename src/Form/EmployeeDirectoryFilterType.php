<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeDirectoryFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('filterField', ChoiceType::class, [
                'label' => 'Filter By',
                'choices' => [
                    'Last Name' => 'lastName',
                    'First Name' => 'firstName',
                    'Middle Name' => 'middleName',
                    'Position' => 'position',
                    'Telegram ID' => 'telegramId',
                ],
                'required' => false,
            ])
            ->add('filterValue', TextType::class, [
                'label' => 'Filter Value',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}