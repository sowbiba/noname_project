<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\DataTransformer\NullToEmptyTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
/**
 * FormType used to manage the creation and the update of delivery types.
 */
class DeliveryTypeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $nullToEmptyTransformer = new NullToEmptyTransformer();

        $builder
            ->add(
                $builder
                    ->create('name', 'Symfony\Component\Form\Extension\Core\Type\TextType',
                        [
                            'label' => 'form.label.name',
                            'required' => true,
                        ]
                    )
                    ->addModelTransformer($nullToEmptyTransformer)
            )
            ->add(
                $builder
                    ->create('delay', 'Symfony\Component\Form\Extension\Core\Type\IntegerType',
                        [
                            'label' => 'form.label.delay',
                            'required' => true,
                        ]
                    )
                    ->addModelTransformer($nullToEmptyTransformer)
            )
            ->add(
                $builder
                    ->create('price', 'Symfony\Component\Form\Extension\Core\Type\IntegerType',
                        [
                            'label' => 'form.label.price',
                            'required' => true,
                        ]
                    )
                    ->addModelTransformer($nullToEmptyTransformer)
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'translation_domain' => 'back_delivery_types',
        ]);
    }
}
