<?php
namespace PoP\UserRolesAccessControl\Conditional\CacheControl\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\CacheControl\DirectiveResolvers\AbstractCacheControlDirectiveResolver;
use PoP\AccessControl\TypeResolverDecorators\AbstractPrivateSchemaTypeResolverDecorator;
use PoP\UserStateAccessControl\TypeResolverDecorators\ValidateConditionForFieldsTypeResolverDecoratorTrait;

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
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $entryList = static::getEntryList();
        $noCacheControlDirectiveResolver = $fieldQueryInterpreter->getDirective(
            AbstractCacheControlDirectiveResolver::getDirectiveName(),
            [
                'maxAge' => 0,
            ]
        );
        foreach ($this->getFieldNames() as $fieldName) {
            if ($matchingEntries = $this->getMatchingEntriesFromConfiguration(
                $entryList,
                $typeResolver,
                $fieldName
            )) {
                foreach ($matchingEntries as $entry) {
                    if ($items = $entry[2]) {
                        $mandatoryDirectivesForFields[$fieldName][] = $noCacheControlDirectiveResolver;
                    }
                }
            }
        }
        return $mandatoryDirectivesForFields;
    }
}
