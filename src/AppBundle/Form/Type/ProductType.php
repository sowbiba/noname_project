<?php

namespace AppBundle\Form\Type;

use AppBundle\Connector\ApiConnector;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
/**
 * FormType used to manage the creation and the update of products.
 */
class ProductType extends AbstractType
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
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',       'Symfony\Component\Form\Extension\Core\Type\TextType',
                [
                    'label' => 'form.label.name',
                    'required' => true,
                ]
            )
            ->add('description','Symfony\Component\Form\Extension\Core\Type\TextareaType',
                [
                    'label' => 'form.label.description',
                    'required' => false,
                ]
            )
            ->add('price', 'Symfony\Component\Form\Extension\Core\Type\MoneyType',
                [
                    'label' => 'form.label.price',
                    'required' => true,
                ]
            )
            ->add('photoFile',  'Symfony\Component\Form\Extension\Core\Type\FileType',
                [
                    'label' => 'form.label.photo_file',
                    'required' => true,
                ]
            )
            ->add(
                'productType',
                'Symfony\Component\Form\Extension\Core\Type\ChoiceType',
                [
                    'label' => 'form.label.product_type',
                    'required' => true,
                    'choices' => $this->getProductTypeChoiceList(),
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
