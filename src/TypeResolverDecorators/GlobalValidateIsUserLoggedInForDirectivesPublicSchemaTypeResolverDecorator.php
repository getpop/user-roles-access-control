<?php
namespace PoP\UserRolesAccessControl\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\UserStateAccessControl\TypeResolverDecorators\AbstractValidateIsUserLoggedInForDirectivesPublicSchemaTypeResolverDecorator;
use PoP\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver;
use PoP\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver;

class GlobalValidateIsUserLoggedInForDirectivesPublicSchemaTypeResolverDecorator extends AbstractValidateIsUserLoggedInForDirectivesPublicSchemaTypeResolverDecorator
{
    public static function getClassesToAttachTo(): array
    {
        return array(
            AbstractTypeResolver::class,
        );
    }

    /**
     * Provide the classes for all the directiveResolverClasses that need the "validateIsUserLoggedIn" directive
     *
     * @return array
     */
    protected function getDirectiveResolverClasses(): array
    {
        return [
            ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver::class,
            ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver::class,
        ];
    }
}
