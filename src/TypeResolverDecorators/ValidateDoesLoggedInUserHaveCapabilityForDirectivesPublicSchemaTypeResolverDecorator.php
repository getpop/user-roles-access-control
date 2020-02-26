<?php
namespace PoP\UserRolesAccessControl\TypeResolverDecorators;

use PoP\UserRolesAccessControl\ComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator;
use PoP\UserRoles\Conditional\UserState\DirectiveResolvers\ValidateDoesLoggedInUserHaveCapabilityDirectiveResolver;
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
        $configuredEntryList = ComponentConfiguration::getRestrictedDirectivesByUserCapability();
        $directiveName = ValidateDoesLoggedInUserHaveCapabilityDirectiveResolver::getDirectiveName();
        $directiveResolverClassCapabilities = [];
        foreach ($configuredEntryList as $entry) {
            $directiveResolverClass = $entry[0];
            $capability = $entry[1];
            $directiveResolverClassCapabilities[$directiveResolverClass][] = $capability;
        }
        foreach ($directiveResolverClassCapabilities as $directiveResolverClass => $capabilities) {
            $validateDoesLoggedInUserHaveCapabilityDirective = $fieldQueryInterpreter->getDirective(
                $directiveName,
                [
                    'capabilities' => $capabilities,
                ]
            );
            $mandatoryDirectivesForDirectives[$directiveResolverClass::getDirectiveName()] = [
                $validateDoesLoggedInUserHaveCapabilityDirective,
            ];
        }
        return $mandatoryDirectivesForDirectives;
    }
}
