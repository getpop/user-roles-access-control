<?php
namespace PoP\UserRolesAccessControl\Conditional\CacheControl\TypeResolverDecorators;

use PoP\CacheControl\Helpers\CacheControlHelper;
use PoP\AccessControl\TypeResolverDecorators\AbstractConfigurableAccessControlForFieldsInPrivateSchemaTypeResolverDecorator;

abstract class AbstractValidateDoesLoggedInUserHaveItemForFieldsPrivateSchemaTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPrivateSchemaTypeResolverDecorator
{
    protected function getMandatoryDirectives($entryValues = null): array
    {
        return [
            CacheControlHelper::getNoCacheDirective(),
        ];
    }
}
