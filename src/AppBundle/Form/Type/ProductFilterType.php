<?php

namespace AppBundle\Form\Type;

use AppBundle\Connector\ApiConnector;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type use to filter products on BACK.
 */
class ProductFilterType extends AbstractType
{
    /**
     * @var ApiConnector
     */
    private $connector;

    /**
     * @param ApiConnector $connector
     */
    public function __construct(ApiConnector $connector)
    {
        $this->connector = $connector;
    }

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
            ->add('price', 'Symfony\Component\Form\Extension\Core\Type\MoneyType',
                [
                    'label' => 'search.label.price',
                    'required' => false,
                ]
            )
            ->add('productType', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType',
                [
                    'label' => 'search.label.productType',
                    'required' => false,
                    'choices' => $this->getProductTypeChoiceList(),
                    'empty_data' => array('form.empty_value.product_type' => null),
                ]
            )
        ;
    }

    /**
     * @return mixed
     */
    private function getProductTypeChoiceList()
    {
        $productTypeList = $this->connector->request(
            ApiConnector::HTTP_METHOD_GET,
            '/product-types',
            ['query' => []] //'orderBy' => 'name asc'
        );

        $productTypes = array_reduce($productTypeList['_embedded']['productTypes'], function ($result, $item) {
            $result[$item['name']] = $item['id'];

            return $result;
        }, []);

        return $productTypes;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'back_product_filter';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'translation_domain' => 'back_products',
        ]);
    }
}
