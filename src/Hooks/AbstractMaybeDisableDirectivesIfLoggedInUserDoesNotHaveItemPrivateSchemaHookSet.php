<?php
namespace PoP\UserRolesAccessControl\Hooks;

use PoP\ComponentModel\State\ApplicationState;
use PoP\AccessControl\Hooks\AbstractConfigurableAccessControlForDirectivesInPrivateSchemaHookSet;
use PoP\AccessControl\Environment;

abstract class AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveItemPrivateSchemaHookSet extends AbstractConfigurableAccessControlForDirectivesInPrivateSchemaHookSet
{
    protected $directiveResolverClasses;

    protected function enabled(): bool
    {
        return parent::enabled() && !empty($this->getDirectiveResolverClasses());
    }

    /**
     * Indicate if the user has the item, to be implemented
     *
     * @param string $item
     * @return boolean
     */
    abstract protected function doesCurrentUserHaveAnyItem(array $items): bool;

    protected function isPublicPrivateSchemaModeValidForEntry(?string $entryIndividualSchemaMode): bool
    {
        if (!Environment::enableIndividualControlForPublicPrivateSchemaMode()) {
            return true;
        }
        $individualControlSchemaMode = $this->getSchemaMode();
        return
            $entryIndividualSchemaMode == $individualControlSchemaMode ||
            (
                is_null($entryIndividualSchemaMode) &&
                $this->doesSchemaModeProcessNullControlEntry()
            );
    }


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
            $entries = $this->getEntries();
            // If the user is not logged in, then it's all directives
            $vars = ApplicationState::getVars();
            $this->directiveResolverClasses = [];
            if (!$vars['global-userstate']['is-user-logged-in']) {
                foreach ($entries as $entry) {
                    $directiveResolverClass = $entry[0];
                    if ($this->isPublicPrivateSchemaModeValidForEntry($entry[2])) {
                        $this->directiveResolverClasses[] = $directiveResolverClass;
                    }
                }
            } else {
                // For each entry, validate if the current user has any of those items (roles/capabilities). If not, the directive must be removed
                foreach ($entries as $entry) {
                    $directiveResolverClass = $entry[0];
                    $items = $entry[1];
                    if (
                        !$this->doesCurrentUserHaveAnyItem($items) &&
                        $this->isPublicPrivateSchemaModeValidForEntry($entry[2])
                    ) {
                        $this->directiveResolverClasses[] = $directiveResolverClass;
                    }
                }
            }
        }
        return $this->directiveResolverClasses;
    }
}
