<?php
namespace PoP\UserRolesAccessControl\Hooks;

use PoP\UserRolesAccessControl\ComponentConfiguration;
use PoP\UserRolesAccessControl\Helpers\UserRoleHelper;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\UserStateAccessControl\Hooks\AbstractMaybeDisableFieldsIfUserNotLoggedInPrivateSchemaHookSet;
use PoP\UserStateAccessControl\Hooks\MaybeDisableFieldsIfConditionPrivateSchemaHookSetTrait;

class MaybeDisableFieldsIfLoggedInUserDoesNotHaveRolePrivateSchemaHookSet extends AbstractMaybeDisableFieldsIfUserNotLoggedInPrivateSchemaHookSet
{
    use MaybeDisableFieldsIfConditionPrivateSchemaHookSetTrait;

    /**
     * Configuration entries
     *
     * @return array
     */
    protected static function getConfiguredEntryList(): array
    {
        return ComponentConfiguration::getRestrictedFieldsByUserRole();
    }

    /**
     * Decide if to remove the fieldNames
     *
     * @param TypeResolverInterface $typeResolver
     * @param FieldResolverInterface $fieldResolver
     * @param string $fieldName
     * @return boolean
     */
    protected function removeFieldName(TypeResolverInterface $typeResolver, FieldResolverInterface $fieldResolver, string $fieldName): bool
    {
        // If the user is not logged in, then remove the field
        $isUserLoggedIn = $this->isUserLoggedIn();
        if (!$isUserLoggedIn) {
            return true;
        }

        // Obtain all roles allowed for the current combination of typeResolver/fieldName
        if ($matchingEntries = $this->getMatchingEntriesFromConfiguration(
            ComponentConfiguration::getRestrictedFieldsByUserRole(),
            $typeResolver,
            $fieldName
        )) {
            $roles = array_values(array_unique(array_map(
                function($entry) {
                    return $entry[2];
                },
                $matchingEntries
            )));
            // Check if the current user has any of the required roles, then access is granted, otherwise reject it
            return !UserRoleHelper::doesCurrentUserHaveAnyRole($roles);
        }
        return false;
    }
}
