<?php
namespace PoP\UserRolesAccessControl;

class Environment
{
    public const RESTRICTED_FIELDS_BY_USER_ROLE = 'RESTRICTED_FIELDS_BY_USER_ROLE';
    public const RESTRICTED_FIELDS_BY_USER_CAPABILITY = 'RESTRICTED_FIELDS_BY_USER_CAPABILITY';
    public const RESTRICTED_DIRECTIVES_BY_USER_ROLE = 'RESTRICTED_DIRECTIVES_BY_USER_ROLE';
    public const RESTRICTED_DIRECTIVES_BY_USER_CAPABILITY = 'RESTRICTED_DIRECTIVES_BY_USER_CAPABILITY';

    public static function getRestrictedFieldsByUserRole(): array
    {
        return isset($_ENV[self::RESTRICTED_FIELDS_BY_USER_ROLE]) ? json_decode($_ENV[self::RESTRICTED_FIELDS_BY_USER_ROLE]) : [];
    }

    public static function getRestrictedFieldsByUserCapability(): array
    {
        return isset($_ENV[self::RESTRICTED_FIELDS_BY_USER_CAPABILITY]) ? json_decode($_ENV[self::RESTRICTED_FIELDS_BY_USER_CAPABILITY]) : [];
    }

    public static function getRestrictedDirectivesByUserRole(): array
    {
        return isset($_ENV[self::RESTRICTED_DIRECTIVES_BY_USER_ROLE]) ? json_decode($_ENV[self::RESTRICTED_DIRECTIVES_BY_USER_ROLE]) : [];
    }

    public static function getRestrictedDirectivesByUserCapability(): array
    {
        return isset($_ENV[self::RESTRICTED_DIRECTIVES_BY_USER_CAPABILITY]) ? json_decode($_ENV[self::RESTRICTED_DIRECTIVES_BY_USER_CAPABILITY]) : [];
    }
}

