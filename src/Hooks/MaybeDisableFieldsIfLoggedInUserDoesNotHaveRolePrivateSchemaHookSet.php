<?php
namespace PoP\UserRolesAccessControl\Hooks;

use PoP\UserRolesAccessControl\ComponentConfiguration;
use PoP\UserRolesAccessControl\Helpers\UserRoleHelper;
use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\UserRolesAccessControl\Services\AccessControlGroups;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\UserStateAccessControl\Hooks\MaybeDisableFieldsIfConditionPrivateSchemaHookSetTrait;
use PoP\UserStateAccessControl\Hooks\AbstractMaybeDisableFieldsIfUserNotLoggedInPrivateSchemaHookSet;

class MaybeDisableFieldsIfLoggedInUserDoesNotHaveRolePrivateSchemaHookSet extends AbstractMaybeDisableFieldsIfUserNotLoggedInPrivateSchemaHookSet
{
    use MaybeDisableFieldsIfConditionPrivateSchemaHookSetTrait;

    /**
     * Configuration entries
     *
     * @return array
     */
    protected static function getEntryList(): array
    {
        $accessControlManager = AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForFields(AccessControlGroups::ROLES);
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
            static::getEntryList(),
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
