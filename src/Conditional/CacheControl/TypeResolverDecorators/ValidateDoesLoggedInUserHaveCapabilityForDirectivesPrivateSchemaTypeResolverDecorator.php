<?php

declare(strict_types=1);

namespace PoP\UserRolesAccessControl\Conditional\CacheControl\TypeResolverDecorators;

use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoP\UserRolesAccessControl\Services\AccessControlGroups;
use PoP\UserRolesAccessControl\Conditional\CacheControl\TypeResolverDecorators\AbstractValidateDoesLoggedInUserHaveItemForDirectivesPrivateSchemaTypeResolverDecorator;

class ValidateDoesLoggedInUserHaveCapabilityForDirectivesPrivateSchemaTypeResolverDecorator extends AbstractValidateDoesLoggedInUserHaveItemForDirectivesPrivateSchemaTypeResolverDecorator
{
    protected function getConfigurationEntries(): array
    {
        $accessControlManager = AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForDirectives(AccessControlGroups::CAPABILITIES);
    }
}
