<?php

namespace AppBundle\Form\Type;

use AppBundle\Connector\ApiConnector;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type use to filter users on BACK.
 */
class UserFilterType extends AbstractType
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
            ->add('email', 'Symfony\Component\Form\Extension\Core\Type\TextType',
                [
                    'label' => 'search.label.email',
                    'required' => false,
                ]
            )
            ->add('username', 'Symfony\Component\Form\Extension\Core\Type\TextType',
                [
                    'label' => 'search.label.username',
                    'required' => false,
                ]
            )
            ->add('active', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType',
                [
                    'label' => 'search.label.active',
                    'required' => false,
                    'choices' => ['form.choice.active.1' => 1, 'form.choice.active.0' => 0],
                    'empty_data' => array('form.empty_value.active' => null),
                ]
            )
            ->add('roleId', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType',
                [
                    'label' => 'search.label.role',
                    'required' => false,
                    'choices' => $this->getRoleChoiceList(),
                    'empty_data' => 'form.empty_value.role',
                ]
            )
            ->add('name', 'Symfony\Component\Form\Extension\Core\Type\TextType',
                [
                    'label' => 'search.label.lastname',
                    'required' => false,
                ]
            )
            ->add('firstName', 'Symfony\Component\Form\Extension\Core\Type\TextType',
                [
                    'label' => 'search.label.firstname',
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
        return 'back_user_filter';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'translation_domain' => 'back_users',
        ]);
    }

    /**
     * @return mixed
     */
    private function getRoleChoiceList()
    {
        $roleList = $this->connector->request(
            ApiConnector::HTTP_METHOD_GET,
            '/roles',
            ['query' => []] //'orderBy' => 'name asc'
        );

        $roles = array_reduce($roleList['_embedded']['roles'], function ($result, $item) {
            $result[$item['name']] = $item['id'];

            return $result;
        }, []);

        return $roles;
    }
}
