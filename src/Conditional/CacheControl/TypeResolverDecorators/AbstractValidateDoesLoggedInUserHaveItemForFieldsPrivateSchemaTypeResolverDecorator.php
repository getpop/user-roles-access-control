<?php
namespace PoP\UserRolesAccessControl\Conditional\CacheControl\TypeResolverDecorators;

use PoP\CacheControl\Helpers\CacheControlHelper;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\AccessControl\TypeResolverDecorators\AbstractPrivateSchemaTypeResolverDecorator;
use PoP\AccessControl\TypeResolverDecorators\ValidateConditionForFieldsTypeResolverDecoratorTrait;

abstract class AbstractValidateDoesLoggedInUserHaveItemForFieldsPrivateSchemaTypeResolverDecorator extends AbstractPrivateSchemaTypeResolverDecorator
{
    use ValidateConditionForFieldsTypeResolverDecoratorTrait;

    abstract protected static function getEntryList(): array;

    /**
     * By default, only the admin can see the roles from the users
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getMandatoryDirectivesForFields(TypeResolverInterface $typeResolver): array
    {
        $mandatoryDirectivesForFields = [];
        $entryList = static::getEntryList();
        $noCacheControlDirective = CacheControlHelper::getNoCacheDirective();
        foreach ($this->getFieldNames() as $fieldName) {
            if ($matchingEntries = $this->getMatchingEntriesFromConfiguration(
                $entryList,
                $typeResolver,
                $fieldName
            )) {
                foreach ($matchingEntries as $entry) {
                    if ($items = $entry[2]) {
                        $mandatoryDirectivesForFields[$fieldName][] = $noCacheControlDirective;
                    }
                }
            }
        }
        return $mandatoryDirectivesForFields;
    }
}
