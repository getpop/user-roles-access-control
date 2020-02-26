<?php
namespace PoP\UserRolesAccessControl\Hooks;

use PoP\UserRolesAccessControl\ComponentConfiguration;
use PoP\UserRolesAccessControl\Helpers\UserRoleHelper;
use PoP\UserRolesAccessControl\Hooks\AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveItemPrivateSchemaHookSet;

class MaybeDisableDirectivesIfLoggedInUserDoesNotHaveCapabilityPrivateSchemaHookSet extends AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveItemPrivateSchemaHookSet
{
    /**
     * Configuration entries
     *
     * @return array
     */
    protected function getConfiguredEntryList(): array
    {
        return ComponentConfiguration::getRestrictedDirectivesByUserCapability();
    }

    /**
     * Indicate if the user has the item, to be implemented
     *
     * @param string $item
     * @return boolean
     */
    protected function doesCurrentUserHaveItem(string $capability): bool
    {
        return UserRoleHelper::doesCurrentUserHaveCapability($capability);
    }
}
