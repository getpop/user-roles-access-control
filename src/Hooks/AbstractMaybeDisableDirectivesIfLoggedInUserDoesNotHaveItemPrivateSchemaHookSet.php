<?php
namespace PoP\UserRolesAccessControl\Hooks;

use PoP\UserState\Facades\UserStateTypeDataResolverFacade;
use PoP\API\Hooks\AbstractMaybeDisableDirectivesInPrivateSchemaHookSet;

abstract class AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveItemPrivateSchemaHookSet extends AbstractMaybeDisableDirectivesInPrivateSchemaHookSet
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
    abstract protected function getConfiguredEntryList(): array;

    /**
     * Indicate if the user has the item, to be implemented
     *
     * @param string $item
     * @return boolean
     */
    abstract protected function doesCurrentUserHaveItem(string $item): bool;

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
            $configuredEntryList = $this->getConfiguredEntryList();
            // If the user is not logged in, then it's all directives
            $userStateTypeDataResolver = UserStateTypeDataResolverFacade::getInstance();
            if (!$userStateTypeDataResolver->isUserLoggedIn()) {
                $this->directiveResolverClasses = array_values(array_unique(array_map(
                    function($configuredEntry) {
                        return $configuredEntry[0];
                    },
                    $configuredEntryList
                )));
            } else {
                // For each entry, validate if the current user has that item (role/capability). If not, the directive must be removed
                $this->directiveResolverClasses = [];
                $itemDirectiveResolverClasses = [];
                foreach ($configuredEntryList as $entry) {
                    $directiveResolverClass = $entry[0];
                    $item = $entry[1];
                    $itemDirectiveResolverClasses[$item][] = $directiveResolverClass;
                }
                foreach ($itemDirectiveResolverClasses as $item => $directiveResolverClasses) {
                    if (!$this->doesCurrentUserHaveItem($item)) {
                        $this->directiveResolverClasses = array_merge(
                            $this->directiveResolverClasses,
                            $directiveResolverClasses
                        );
                    }
                }
            }
        }
        return $this->directiveResolverClasses;
    }
}
