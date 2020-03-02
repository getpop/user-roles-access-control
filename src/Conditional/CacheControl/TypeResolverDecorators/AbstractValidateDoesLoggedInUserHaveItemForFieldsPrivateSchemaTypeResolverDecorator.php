<?php
namespace PoP\UserRolesAccessControl\Conditional\CacheControl\TypeResolverDecorators;

use PoP\CacheControl\Helpers\CacheControlHelper;
use PoP\AccessControl\TypeResolverDecorators\AbstractPrivateSchemaTypeResolverDecorator;
use PoP\AccessControl\TypeResolverDecorators\ConfigurableAccessControlForFieldsTypeResolverDecoratorTrait;

abstract class AbstractValidateDoesLoggedInUserHaveItemForFieldsPrivateSchemaTypeResolverDecorator extends AbstractPrivateSchemaTypeResolverDecorator
{
    use ConfigurableAccessControlForFieldsTypeResolverDecoratorTrait;

    protected function getMandatoryDirectives($entryValues = null): array
    {
        return [
            CacheControlHelper::getNoCacheDirective(),
        ];
    }
}
