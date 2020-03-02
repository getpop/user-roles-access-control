<?php
namespace PoP\UserRolesAccessControl\TypeResolverDecorators;

use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\UserRolesAccessControl\Services\AccessControlGroups;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator;
use PoP\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver;
use PoP\AccessControl\TypeResolverDecorators\ValidateConditionForDirectivesTypeResolverDecoratorTrait;

class ValidateDoesLoggedInUserHaveRoleForDirectivesPublicSchemaTypeResolverDecorator extends AbstractPublicSchemaTypeResolverDecorator
{
    use ValidateConditionForDirectivesTypeResolverDecoratorTrait;

    protected function getConfigurationEntries(): array
    {
        $accessControlManager = AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForDirectives(AccessControlGroups::ROLES);
    }

    /**
     * By default, only the admin can see the roles from the users
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    protected function getMandatoryDirectives($entryValue = null): array
    {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $directiveName = ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver::getDirectiveName();
        $roles = $entryValue;
        $validateDoesLoggedInUserHaveAnyRoleDirective = $fieldQueryInterpreter->getDirective(
            $directiveName,
            [
                'roles' => $roles,
            ]
        );
        return [
            $validateDoesLoggedInUserHaveAnyRoleDirective,
        ];
    }
}
