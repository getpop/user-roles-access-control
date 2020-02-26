<?php
namespace PoP\UserRolesAccessControl\TypeResolverDecorators;

use PoP\UserRolesAccessControl\ComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator;
use PoP\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver;
use PoP\UserStateAccessControl\TypeResolverDecorators\ValidateConditionForDirectivesPublicSchemaTypeResolverDecoratorTrait;

class ValidateDoesLoggedInUserHaveRoleForDirectivesPublicSchemaTypeResolverDecorator extends AbstractPublicSchemaTypeResolverDecorator
{
    use ValidateConditionForDirectivesPublicSchemaTypeResolverDecoratorTrait;

    protected static function getConfiguredEntryList(): array
    {
        return ComponentConfiguration::getRestrictedDirectivesByUserRole();
    }

    /**
     * By default, only the admin can see the roles from the users
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getMandatoryDirectivesForDirectives(TypeResolverInterface $typeResolver): array
    {
        $mandatoryDirectivesForDirectives = [];
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $configuredEntryList = ComponentConfiguration::getRestrictedDirectivesByUserRole();
        $directiveName = ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver::getDirectiveName();
        $directiveResolverClassRoles = [];
        foreach ($configuredEntryList as $entry) {
            $directiveResolverClass = $entry[0];
            $role = $entry[1];
            $directiveResolverClassRoles[$directiveResolverClass][] = $role;
        }
        foreach ($directiveResolverClassRoles as $directiveResolverClass => $roles) {
            $validateDoesLoggedInUserHaveAnyRoleDirective = $fieldQueryInterpreter->getDirective(
                $directiveName,
                [
                    'roles' => $roles,
                ]
            );
            $mandatoryDirectivesForDirectives[$directiveResolverClass::getDirectiveName()] = [
                $validateDoesLoggedInUserHaveAnyRoleDirective,
            ];
        }
        return $mandatoryDirectivesForDirectives;
    }
}
