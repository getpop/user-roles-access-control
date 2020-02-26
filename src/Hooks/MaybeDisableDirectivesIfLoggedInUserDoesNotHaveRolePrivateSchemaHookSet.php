<?php
namespace PoP\UserRolesAccessControl\Hooks;

use PoP\UserRolesAccessControl\ComponentConfiguration;
use PoP\UserRolesAccessControl\Helpers\UserRoleHelper;
use PoP\UserRolesAccessControl\Hooks\AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveItemPrivateSchemaHookSet;

class MaybeDisableDirectivesIfLoggedInUserDoesNotHaveRolePrivateSchemaHookSet extends AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveItemPrivateSchemaHookSet
{
    /**
     * Configuration entries
     *
     * @return array
     */
    protected function getConfiguredEntryList(): array
    {
        return ComponentConfiguration::getRestrictedDirectivesByUserRole();
    }

    /**
     * Indicate if the user has the item, to be implemented
     *
     * @param string $item
     * @return boolean
     */
    protected function doesCurrentUserHaveItem(string $role): bool
    {
        return UserRoleHelper::doesCurrentUserHaveRole($role);
    }
}
