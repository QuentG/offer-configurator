<?php

namespace App\Form;

use App\Entity\Option;
use App\Form\AttributeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class OptionAttributesType extends AbstractType
{
    /**
     * Used to create new option. Then user have to create each attributes related to this option.
     * (We can allow him to choose attributes already existing but it makes no sense right ?)
     **/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('attributes', CollectionType::class, [
                'entry_type' => new AttributeType(),
                'allow_add' => true,
                'prototype' => true,
                'prototype_data' => 'New attribute',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Option::class,
        ]);
    }
}
