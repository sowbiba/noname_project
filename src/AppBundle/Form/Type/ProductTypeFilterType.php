<?php

namespace AppBundle\Form\Type;

use AppBundle\Connector\ApiConnector;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type use to filter product_types on BACK.
 */
class ProductTypeFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name', 'Symfony\Component\Form\Extension\Core\Type\TextType',
                [
                    'label' => 'search.label.name',
                    'required' => false,
                ]
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'back_product_type_filter';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'translation_domain' => 'back_product_types',
        ]);
    }
}
