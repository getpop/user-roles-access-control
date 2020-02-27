<?php
namespace PoP\UserRolesAccessControl\Hooks;

use PoP\UserState\Facades\UserStateTypeDataResolverFacade;
use PoP\UserStateAccessControl\Hooks\AbstractMaybeDisableUserStateDirectivesInPrivateSchemaHookSet;

abstract class AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveItemPrivateSchemaHookSet extends AbstractMaybeDisableUserStateDirectivesInPrivateSchemaHookSet
{
    protected $directiveResolverClasses;

    protected function enabled(): bool
    {
        return parent::enabled() && !empty($this->getDirectiveResolverClasses());
    }

    /**
     * Configuration entries
     *
     * @return array
     */
    abstract protected function getEntryList(): array;

    /**
     * Indicate if the user has the item, to be implemented
     *
     * @param string $item
     * @return boolean
     */
    abstract protected function doesCurrentUserHaveAnyItem(array $items): bool;

    /**
     * Remove directiveName "translate" if the user is not logged in
     *
     * @param boolean $include
     * @param TypeResolverInterface $typeResolver
     * @param string $directiveName
     * @return boolean
     */
    protected function getDirectiveResolverClasses(): array
    {
        if (is_null($this->directiveResolverClasses)) {
            $entryList = $this->getEntryList();
            // If the user is not logged in, then it's all directives
            $userStateTypeDataResolver = UserStateTypeDataResolverFacade::getInstance();
            if (!$userStateTypeDataResolver->isUserLoggedIn()) {
                $this->directiveResolverClasses = array_values(array_unique(array_map(
                    function($entry) {
                        return $entry[0];
                    },
                    $entryList
                )));
            } else {
                // For each entry, validate if the current user has any of those items (roles/capabilities). If not, the directive must be removed
                $this->directiveResolverClasses = [];
                foreach ($entryList as $entry) {
                    $directiveResolverClass = $entry[0];
                    $items = $entry[1];
                    if (!$this->doesCurrentUserHaveAnyItem($items)) {
                        $this->directiveResolverClasses[] = $directiveResolverClass;
                    }
                }
            }
        }
        return $this->directiveResolverClasses;
    }
}
