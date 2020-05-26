<?php

declare(strict_types=1);

namespace PoP\UserRolesAccessControl\Conditional\CacheControl;

use PoP\Root\Component\YAMLServicesTrait;
use PoP\UserRolesAccessControl\Component;
use PoP\ComponentModel\Container\ContainerBuilderUtils;

/**
 * Initialize component
 */
class ConditionalComponent
{
    use YAMLServicesTrait;

    public static function initialize(bool $skipSchema = false): void
    {
        self::maybeInitYAMLSchemaServices(Component::$COMPONENT_DIR, $skipSchema, '/Conditional/CacheControl');
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function afterBoot(): void
    {
        // Initialize classes
        ContainerBuilderUtils::attachTypeResolverDecoratorsFromNamespace(__NAMESPACE__ . '\\TypeResolverDecorators');
    }
}
