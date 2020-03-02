<?php
namespace PoP\UserRolesAccessControl\TypeResolverDecorators;

use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoP\UserRolesAccessControl\Services\AccessControlGroups;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator;
use PoP\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver;
use PoP\AccessControl\TypeResolverDecorators\ConfigurableAccessControlForFieldsTypeResolverDecoratorTrait;

class ValidateDoesLoggedInUserHaveRoleForFieldsPublicSchemaTypeResolverDecorator extends AbstractPublicSchemaTypeResolverDecorator
{
    use ConfigurableAccessControlForFieldsTypeResolverDecoratorTrait;

    protected static function getConfigurationEntries(): array
    {
        $accessControlManager = AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForFields(AccessControlGroups::ROLES);
    }

    protected function getMandatoryDirectives($entryValue = null): array
    {
        $roles = $entryValue;
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $directiveName = ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver::getDirectiveName();
        return [
            $fieldQueryInterpreter->getDirective(
                $directiveName,
                [
                    'roles' => $roles,
                ]
            ),
        ];
    }
}
