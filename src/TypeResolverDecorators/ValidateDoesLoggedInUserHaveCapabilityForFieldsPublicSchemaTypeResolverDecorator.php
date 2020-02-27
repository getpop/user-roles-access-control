<?php
namespace PoP\UserRolesAccessControl\TypeResolverDecorators;

use PoP\UserRolesAccessControl\ComponentConfiguration;
use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\UserRolesAccessControl\Services\AccessControlGroups;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator;
use PoP\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver;
use PoP\UserStateAccessControl\TypeResolverDecorators\ValidateConditionForFieldsPublicSchemaTypeResolverDecoratorTrait;

class ValidateDoesLoggedInUserHaveCapabilityForFieldsPublicSchemaTypeResolverDecorator extends AbstractPublicSchemaTypeResolverDecorator
{
    use ValidateConditionForFieldsPublicSchemaTypeResolverDecoratorTrait;

    protected static function getEntryList(): array
    {
        $accessControlManager = AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForFields(AccessControlGroups::CAPABILITIES);
        // return ComponentConfiguration::getRestrictedFieldsByUserCapability();
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
        $entryList = static::getEntryList();
        $directiveName = ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver::getDirectiveName();
        // Obtain all capabilities allowed for the current combination of typeResolver/fieldName
        foreach ($this->getFieldNames() as $fieldName) {
            if ($matchingEntries = $this->getMatchingEntriesFromConfiguration(
                $entryList,
                $typeResolver,
                $fieldName
            )) {
                if ($capabilities = array_values(array_unique(array_map(
                    function($entry) {
                        return $entry[2];
                    },
                    $matchingEntries
                )))) {
                    $validateDoesLoggedInUserHaveAnyCapabilityDirective = $fieldQueryInterpreter->getDirective(
                        $directiveName,
                        [
                            'capabilities' => $capabilities,
                        ]
                    );
                    $mandatoryDirectivesForFields[$fieldName] = [
                        $validateDoesLoggedInUserHaveAnyCapabilityDirective,
                    ];
                }
            }
        }
        return $mandatoryDirectivesForFields;
    }
}
