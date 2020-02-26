<?php
namespace PoP\UserRolesAccessControl\TypeResolverDecorators;

use PoP\UserRolesAccessControl\ComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator;
use PoP\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver;
use PoP\UserStateAccessControl\TypeResolverDecorators\ValidateConditionForDirectivesPublicSchemaTypeResolverDecoratorTrait;

class ValidateDoesLoggedInUserHaveCapabilityForDirectivesPublicSchemaTypeResolverDecorator extends AbstractPublicSchemaTypeResolverDecorator
{
    use ValidateConditionForDirectivesPublicSchemaTypeResolverDecoratorTrait;

    protected static function getConfiguredEntryList(): array
    {
        return ComponentConfiguration::getRestrictedDirectivesByUserCapability();
    }

    /**
     * By default, only the admin can see the capabilities from the users
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getMandatoryDirectivesForDirectives(TypeResolverInterface $typeResolver): array
    {
        $mandatoryDirectivesForDirectives = [];
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $calledClass = get_called_class();
        $configuredEntryList = $calledClass::getConfiguredEntryList();
        $directiveName = ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver::getDirectiveName();
        $directiveResolverClassCapabilities = [];
        foreach ($configuredEntryList as $entry) {
            $directiveResolverClass = $entry[0];
            $capability = $entry[1];
            $directiveResolverClassCapabilities[$directiveResolverClass][] = $capability;
        }
        foreach ($directiveResolverClassCapabilities as $directiveResolverClass => $capabilities) {
            $validateDoesLoggedInUserHaveAnyCapabilityDirective = $fieldQueryInterpreter->getDirective(
                $directiveName,
                [
                    'capabilities' => $capabilities,
                ]
            );
            $mandatoryDirectivesForDirectives[$directiveResolverClass::getDirectiveName()] = [
                $validateDoesLoggedInUserHaveAnyCapabilityDirective,
            ];
        }
        return $mandatoryDirectivesForDirectives;
    }
}
