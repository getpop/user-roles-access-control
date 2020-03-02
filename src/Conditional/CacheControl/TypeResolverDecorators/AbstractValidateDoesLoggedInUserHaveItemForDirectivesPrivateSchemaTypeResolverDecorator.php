<?php
namespace PoP\UserRolesAccessControl\Conditional\CacheControl\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\AccessControl\TypeResolverDecorators\AbstractConfigurableAccessControlForDirectivesInPrivateSchemaTypeResolverDecorator;
use PoP\CacheControl\Helpers\CacheControlHelper;

abstract class AbstractValidateDoesLoggedInUserHaveItemForDirectivesPrivateSchemaTypeResolverDecorator extends AbstractConfigurableAccessControlForDirectivesInPrivateSchemaTypeResolverDecorator
{
    /**
     * By default, only the admin can see the capabilities from the users
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    protected function getMandatoryDirectives($entryValue = null): array
    {
        return [
            CacheControlHelper::getNoCacheDirective(),
        ];
    }
}
