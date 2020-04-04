<?php
namespace PoP\UserRolesAccessControl\DirectiveResolvers;

class ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver extends ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver
{
    const DIRECTIVE_NAME = 'validateDoesLoggedInUserHaveAnyRoleForDirectives';
    public static function getDirectiveName(): string
    {
        return self::DIRECTIVE_NAME;
    }

    protected function isValidatingDirective(): bool
    {
        return true;
    }
}
