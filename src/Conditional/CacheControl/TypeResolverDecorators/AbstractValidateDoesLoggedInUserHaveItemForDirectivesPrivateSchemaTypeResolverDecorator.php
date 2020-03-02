<?php
namespace PoP\UserRolesAccessControl\Conditional\CacheControl\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\AccessControl\TypeResolverDecorators\AbstractPrivateSchemaTypeResolverDecorator;
use PoP\CacheControl\Helpers\CacheControlHelper;
use PoP\AccessControl\TypeResolverDecorators\ValidateConditionForDirectivesTypeResolverDecoratorTrait;

abstract class AbstractValidateDoesLoggedInUserHaveItemForDirectivesPrivateSchemaTypeResolverDecorator extends AbstractPrivateSchemaTypeResolverDecorator
{
    use ValidateConditionForDirectivesTypeResolverDecoratorTrait;

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
