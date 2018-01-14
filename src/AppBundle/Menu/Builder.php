<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class Builder.
 *
 * @codeCoverageIgnore
 */
class Builder
{
    /**
     * @param FactoryInterface $factory
     *
     * @return \Knp\Menu\ItemInterface
     */
//    public function mainMenu(FactoryInterface $factory)
//    {
//        $router = $this->container->get('router');
//
//        $translator = $this->container->get('translator');
//        $menu = $factory->createItem('root', ['navbar' => true]);
//
//        $menu->setChildrenAttributes(
//            [
//                'class' => 'nav navbar-nav',
//            ]
//        );
//
//        //menu for contract sets
//        $menu
//            ->addChild('menu.contract_sets', ['uri' => $router->generate('back_contract_set_list')])
//            ->setExtra('translation_domain', 'back')
//            ->setAttributes([
//                'icon' => 'fa fa-empire',
//                'caret' => true,
//            ]);
//
//        //dropdown for users and groups
//        $dropdown = $menu
//            ->addChild('menu.users_and_groups')
//            ->setExtra('translation_domain', 'back')
//            ->setAttributes([
//                'icon' => 'fa fa-users',
//                'dropdown' => true,
//                'caret' => true,
//            ]);
//        $dropdown
//            ->addChild('menu.users', ['uri' => $router->generate('back_user_list'), 'dropdown-header' => true])
//            ->setExtra('translation_domain', 'back')
//        ;
//        $dropdown
//            ->addChild('menu.groups', ['uri' => $router->generate('back_group_list')])
//            ->setExtra('translation_domain', 'back')
//        ;
//        $dropdown
//            ->addChild('menu.front_application_messages', ['uri' => $router->generate('back_front_application_message_update', ['frontSlug' => 'standard'])])
//            ->setExtra('translation_domain', 'back')
//        ;
//
//        //menu for stats
//        $menu
//            ->addChild('menu.stats', ['uri' => $router->generate('back_stats')])
//            ->setExtra('translation_domain', 'back')
//            ->setAttributes([
//                'icon' => 'fa fa-table',
//                'caret' => true,
//            ]);
//
//        //menu for job queues
//        $menu
//            ->addChild('menu.job_queues', ['uri' => $router->generate('back_job_queue')])
//            ->setExtra('translation_domain', 'back')
//            ->setAttributes([
//                'icon' => 'fa fa-list',
//                'caret' => true,
//            ]);
//
//        //dropdown for FRONT
//        $dropdownFront = $menu
//            ->addChild('menu.front.title')
//            ->setExtra('translation_domain', 'back')
//            ->setAttributes(['icon' => 'fa fa-eye', 'dropdown' => true, 'caret' => true])
//        ;
//
//        // FRONT STANDARD
//        $frontUri = $router->generate('front_homepage');
//        $dropdownFront
//            ->addChild('menu.front.standard.title', ['uri' => '', 'dropdown-header' => true])
//            ->setExtra('translation_domain', 'back')
//            ->setLinkAttributes(['target' => '_blank'])
//            ->setAttributes(['class' => 'dropdown-header-section'])
//        ;
//        $dropdownFront
//            ->addChild('menu.front.standard.draft', ['uri' => '/draft'.$frontUri, 'dropdown-header' => true])
//            ->setExtra('translation_domain', 'back')
//            ->setLinkAttributes(['target' => '_blank'])
//        ;
//        $dropdownFront
//            ->addChild('menu.front.standard.demo', ['uri' => '/demo'.$frontUri, 'dropdown-header' => true])
//            ->setExtra('translation_domain', 'back')
//            ->setLinkAttributes(['target' => '_blank'])
//        ;
//        $dropdownFront
//            ->addChild('menu.front.standard.publish', ['uri' => $frontUri, 'dropdown-header' => true])
//            ->setExtra('translation_domain', 'back')
//            ->setLinkAttributes(['target' => '_blank'])
//            ->setAttribute('divider_append', true)
//        ;
//        // FRONT DIAG
//        $frontUri = $router->generate('front_diag_homepage');
//        $dropdownFront
//            ->addChild('menu.front.diag.title', ['uri' => '', 'dropdown-header' => true])
//            ->setExtra('translation_domain', 'back')
//            ->setLinkAttributes(['target' => '_blank'])
//            ->setAttributes(['class' => 'dropdown-header-section'])
//        ;
//        $dropdownFront
//            ->addChild('menu.front.diag.draft', ['uri' => '/draft'.$frontUri, 'dropdown-header' => true])
//            ->setExtra('translation_domain', 'back')
//            ->setLinkAttributes(['target' => '_blank'])
//        ;
//        $dropdownFront
//            ->addChild('menu.front.diag.demo', ['uri' => '/demo'.$frontUri, 'dropdown-header' => true])
//            ->setExtra('translation_domain', 'back')
//            ->setLinkAttributes(['target' => '_blank'])
//        ;
//        $dropdownFront
//            ->addChild('menu.front.diag.publish', ['uri' => $frontUri, 'dropdown-header' => true])
//            ->setExtra('translation_domain', 'back')
//            ->setLinkAttributes(['target' => '_blank'])
//            ->setAttribute('divider_append', true)
//        ;
//        // FRONT GEASSUR
//        $frontUri = $router->generate('front_geassur_homepage');
//        $dropdownFront
//            ->addChild('menu.front.geassur.title', ['uri' => '', 'dropdown-header' => true])
//            ->setExtra('translation_domain', 'back')
//            ->setLinkAttributes(['target' => '_blank'])
//            ->setAttributes(['class' => 'dropdown-header-section'])
//        ;
//        $dropdownFront
//            ->addChild('menu.front.geassur.draft', ['uri' => '/draft'.$frontUri, 'dropdown-header' => true])
//            ->setExtra('translation_domain', 'back')
//            ->setLinkAttributes(['target' => '_blank'])
//        ;
//        $dropdownFront
//            ->addChild('menu.front.geassur.demo', ['uri' => '/demo'.$frontUri, 'dropdown-header' => true])
//            ->setExtra('translation_domain', 'back')
//            ->setLinkAttributes(['target' => '_blank'])
//        ;
//        $dropdownFront
//            ->addChild('menu.front.geassur.publish', ['uri' => $frontUri, 'dropdown-header' => true])
//            ->setExtra('translation_domain', 'back')
//            ->setLinkAttributes(['target' => '_blank'])
//            ->setAttribute('divider_append', true)
//        ;
//        // FRONT PROPALE
//        $frontUri = $router->generate('front_propale_homepage');
//        $dropdownFront
//            ->addChild('menu.front.propale.title', ['uri' => '', 'dropdown-header' => true])
//            ->setExtra('translation_domain', 'back')
//            ->setLinkAttributes(['target' => '_blank'])
//            ->setAttributes(['class' => 'dropdown-header-section'])
//        ;
//        $dropdownFront
//            ->addChild('menu.front.propale.draft', ['uri' => '/draft'.$frontUri, 'dropdown-header' => true])
//            ->setExtra('translation_domain', 'back')
//            ->setLinkAttributes(['target' => '_blank'])
//        ;
//        $dropdownFront
//            ->addChild('menu.front.propale.demo', ['uri' => '/demo'.$frontUri, 'dropdown-header' => true])
//            ->setExtra('translation_domain', 'back')
//            ->setLinkAttributes(['target' => '_blank'])
//        ;
//        $dropdownFront
//            ->addChild('menu.front.propale.publish', ['uri' => $frontUri, 'dropdown-header' => true])
//            ->setExtra('translation_domain', 'back')
//            ->setLinkAttributes(['target' => '_blank'])
//        ;
//
//        return $menu;
//    }

    /**
     * @param FactoryInterface $factory
     * @param array            $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function productMenu(FactoryInterface $factory, array $options)
    {
        if (!isset($options['productId']) || null === $options['productId']) {
            throw new \BadMethodCallException('`productId` option is required for productMenu menu.');
        }

        if (!isset($options['view']) || !in_array($options['view'], ['dropdown-menu', 'nav-tabs'], true)) {
            throw new \BadMethodCallException(
                '`view` option is required for productMenu menu with `dropdown-menu` or `nav-tabs` value.'
            );
        }

        //$request = $this->container->get('request_stack')->getCurrentRequest();

        $productId = $options['productId'];

        $menu = $factory->createItem('root');

        $menu->addChild('Configuration', ['uri' => '']);
        $menu['Configuration']->addChild('Gestion de stock', ['route' => 'back_product_stock_manage', 'routeParameters' => ['productId' => $productId]]);

        if ('dropdown-menu' === $options['view']) {
            $menu->setChildrenAttribute('class', 'dropdown-menu');
            $menu['Configuration']
                ->setAttribute('class', 'dropdown-header')
                ->setAttribute('divider_append', true);
        }

        return $menu;
    }
}