<?php
namespace PoP\UserRolesAccessControl;

use PoP\ComponentModel\AbstractComponentConfiguration;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private static $getRestrictedFieldsByUserRole;
    private static $getRestrictedFieldsByUserCapability;
    private static $getRestrictedDirectivesByUserRole;
    private static $getRestrictedDirectivesByUserCapability;

    public static function getRestrictedFieldsByUserRole(): array
    {
        // Define properties
        $envVariable = Environment::RESTRICTED_FIELDS_BY_USER_ROLE;
        $selfProperty = &self::$getRestrictedFieldsByUserRole;
        $callback = [Environment::class, 'getRestrictedFieldsByUserRole'];

        // Initialize property from the environment/hook
        self::maybeInitEnvironmentVariable(
            $envVariable,
            $selfProperty,
            $callback
        );
        return $selfProperty;
    }

    public static function getRestrictedFieldsByUserCapability(): array
    {
        // Define properties
        $envVariable = Environment::RESTRICTED_FIELDS_BY_USER_CAPABILITY;
        $selfProperty = &self::$getRestrictedFieldsByUserCapability;
        $callback = [Environment::class, 'getRestrictedFieldsByUserCapability'];

        // Initialize property from the environment/hook
        self::maybeInitEnvironmentVariable(
            $envVariable,
            $selfProperty,
            $callback
        );
        return $selfProperty;
    }

    public static function getRestrictedDirectivesByUserRole(): array
    {
        // Define properties
        $envVariable = Environment::RESTRICTED_DIRECTIVES_BY_USER_ROLE;
        $selfProperty = &self::$getRestrictedDirectivesByUserRole;
        $callback = [Environment::class, 'getRestrictedDirectivesByUserRole'];

        // Initialize property from the environment/hook
        self::maybeInitEnvironmentVariable(
            $envVariable,
            $selfProperty,
            $callback
        );
        return $selfProperty;
    }

    public static function getRestrictedDirectivesByUserCapability(): array
    {
        // Define properties
        $envVariable = Environment::RESTRICTED_DIRECTIVES_BY_USER_CAPABILITY;
        $selfProperty = &self::$getRestrictedDirectivesByUserCapability;
        $callback = [Environment::class, 'getRestrictedDirectivesByUserCapability'];

        // Initialize property from the environment/hook
        self::maybeInitEnvironmentVariable(
            $envVariable,
            $selfProperty,
            $callback
        );
        return $selfProperty;
    }
}

