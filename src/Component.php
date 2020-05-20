<?php

declare(strict_types=1);

namespace PoP\UserRolesAccessControl;

use PoP\AccessControl\Component as AccessControlComponent;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use PoP\Root\Component\CanDisableComponentTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    public static $COMPONENT_DIR;
    use YAMLServicesTrait, CanDisableComponentTrait;
    // const VERSION = '0.1.0';

    public static function getDependedComponentClasses(): array
    {
        return [
            \PoP\UserRoles\Component::class,
            \PoP\UserStateAccessControl\Component::class,
        ];
    }

    /**
     * Initialize services
     */
    public static function init()
    {
        if (self::isEnabled()) {
            parent::init();
            self::$COMPONENT_DIR = dirname(__DIR__);
            self::initYAMLServices(self::$COMPONENT_DIR);

            if (class_exists('\PoP\CacheControl\Component')) {
                \PoP\UserRolesAccessControl\Conditional\CacheControl\ConditionalComponent::init();
            }
        }
    }

    protected static function resolveEnabled()
    {
        return AccessControlComponent::isEnabled();
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot()
    {
        parent::beforeBoot();

        // Initialize classes
        ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\Hooks');
        ContainerBuilderUtils::attachAndRegisterDirectiveResolversFromNamespace(__NAMESPACE__ . '\\DirectiveResolvers');// Boot conditional on API package being installed
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function afterBoot()
    {
        parent::afterBoot();

        // Initialize classes
        ContainerBuilderUtils::attachTypeResolverDecoratorsFromNamespace(__NAMESPACE__ . '\\TypeResolverDecorators');

        if (class_exists('\PoP\CacheControl\Component')) {
            \PoP\UserRolesAccessControl\Conditional\CacheControl\ConditionalComponent::afterBoot();
        }
    }
}
