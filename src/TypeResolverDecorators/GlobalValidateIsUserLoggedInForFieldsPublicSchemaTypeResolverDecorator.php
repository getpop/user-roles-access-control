<?php

declare(strict_types=1);

namespace PoP\UserRolesAccessControl\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\UserStateAccessControl\TypeResolverDecorators\AbstractValidateIsUserLoggedInForFieldsPublicSchemaTypeResolverDecorator;
use PoP\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver;
use PoP\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver;

class GlobalValidateIsUserLoggedInForFieldsPublicSchemaTypeResolverDecorator extends AbstractValidateIsUserLoggedInForFieldsPublicSchemaTypeResolverDecorator
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
            ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver::class,
            ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver::class,
        ];
    }
}
