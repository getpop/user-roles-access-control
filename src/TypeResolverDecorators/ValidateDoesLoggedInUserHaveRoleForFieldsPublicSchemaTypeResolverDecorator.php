<?php
namespace PoP\UserRolesAccessControl\TypeResolverDecorators;

use PoP\UserRolesAccessControl\ComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator;
use PoP\UserRoles\Conditional\UserState\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver;
use PoP\UserStateAccessControl\TypeResolverDecorators\ValidateConditionForFieldsPublicSchemaTypeResolverDecoratorTrait;

class ValidateDoesLoggedInUserHaveRoleForFieldsPublicSchemaTypeResolverDecorator extends AbstractPublicSchemaTypeResolverDecorator
{
    use ValidateConditionForFieldsPublicSchemaTypeResolverDecoratorTrait;

    protected static function getConfiguredEntryList(): array
    {
        return ComponentConfiguration::getRestrictedFieldsByUserRole();
    }

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
        $configuredEntryList = ComponentConfiguration::getRestrictedFieldsByUserRole();
        $directiveName = ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver::getDirectiveName();
        // Obtain all roles allowed for the current combination of typeResolver/fieldName
        foreach ($this->getFieldNames() as $fieldName) {
            if ($matchingEntries = $this->getMatchingEntriesFromConfiguration(
                $configuredEntryList,
                $typeResolver,
                $fieldName
            )) {
                if ($roles = array_values(array_unique(array_map(
                    function($entry) {
                        return $entry[2];
                    },
                    $matchingEntries
                )))) {
                    $validateDoesLoggedInUserHaveAnyRoleDirective = $fieldQueryInterpreter->getDirective(
                        $directiveName,
                        [
                            'roles' => $roles,
                        ]
                    );
                    $mandatoryDirectivesForFields[$fieldName] = [
                        $validateDoesLoggedInUserHaveAnyRoleDirective,
                    ];
                }
            }
        }
        return $mandatoryDirectivesForFields;
    }
}
