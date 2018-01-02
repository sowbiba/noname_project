<?php

namespace AppBundle\Form\Type;

use AppBundle\Connector\ApiConnector;
use AppBundle\Form\DataTransformer\NullToEmptyTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserType.
 */
class UserType extends AbstractType
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
        $nullToEmptyTransformer = new NullToEmptyTransformer();

        $builder
            ->add(
                $builder
                    ->create('active', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
                        [
                            'label' => 'form.label.active',
                            'required' => false,
                        ]
                    )
                    ->addModelTransformer($nullToEmptyTransformer)
            )
            ->add(
                $builder
                    ->create('address', 'Symfony\Component\Form\Extension\Core\Type\TextareaType',
                        [
                            'label' => 'form.label.address',
                            'required' => false,
                        ]
                    )
                    ->addModelTransformer($nullToEmptyTransformer)
            )
            ->add(
                $builder
                    ->create('email', 'Symfony\Component\Form\Extension\Core\Type\EmailType',
                        [
                            'label' => 'form.label.email',
                            'required' => false,
                        ]
                    )
                    ->addModelTransformer($nullToEmptyTransformer)
            )
            ->add(
                $builder
                    ->create('firstname', 'Symfony\Component\Form\Extension\Core\Type\TextType',
                        [
                            'label' => 'form.label.firstname',
                            'required' => false,
                        ]
                    )
                    ->addModelTransformer($nullToEmptyTransformer)
            )
            ->add(
                $builder
                    ->create('lastname', 'Symfony\Component\Form\Extension\Core\Type\TextType',
                        [
                            'label' => 'form.label.lastname',
                            'required' => false,
                        ]
                    )
                    ->addModelTransformer($nullToEmptyTransformer)
            )
            ->add(
                $builder
                    ->create('username', 'Symfony\Component\Form\Extension\Core\Type\TextType',
                        [
                            'label' => 'form.label.username',
                            'required' => true,
                        ]
                    )
                    ->addModelTransformer($nullToEmptyTransformer)
            )
            ->add(
                $builder
                    ->create('password', 'Symfony\Component\Form\Extension\Core\Type\PasswordType',
                        [
                            'label' => 'form.label.password',
                            'required' => false,
                        ]
                    )
                    ->addModelTransformer($nullToEmptyTransformer)
            )
            ->add(
                $builder
                    ->create('phone', 'Symfony\Component\Form\Extension\Core\Type\TextType',
                        [
                            'label' => 'form.label.phone',
                            'required' => false,
                        ]
                    )
                    ->addModelTransformer($nullToEmptyTransformer)
            )
            ->add(
                $builder
                    ->create('role', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType',
                        [
                            'label' => 'form.label.role',
                            'required' => true,
                            'choices' => $this->getRoleChoiceList(),
                            'empty_data' => 'form.empty_value.role',
                        ]
                    )
                    ->addModelTransformer($nullToEmptyTransformer)
            )
            ->add(
                $builder
                    ->create('birthdate', 'Symfony\Component\Form\Extension\Core\Type\TextType',
                        [
                            'label' => 'form.label.birthdate',
                            'required' => false,
                        ]
                    )
                    ->addModelTransformer($nullToEmptyTransformer)
            )
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            if (null !== $data) {
                if (isset($data['role']['id'])) {
                    $data['role'] = $data['role']['id'];
                }
            }

            $event->setData($data);
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $data = $event->getData();

            if (isset($data['birthdate']) && !empty($data['birthdate']) && preg_match('/.*\-.*\-.*/', $data['birthdate'])) {
                $birthdate = \DateTime::createFromFormat('Y-m-d', $data['birthdate']);
                $data['birthdate'] = $birthdate->format('d/m/Y');
            }

            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = implode(', ', array_filter(array_map('trim', $value)));
                }
            }

            $event->setData($data);
        });
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

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'user';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'translation_domain' => 'back_users',
        ]);
    }
}
