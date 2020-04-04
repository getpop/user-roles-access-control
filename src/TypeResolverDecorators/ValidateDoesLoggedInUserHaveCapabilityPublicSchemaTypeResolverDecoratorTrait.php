<?php
namespace PoP\UserRolesAccessControl\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver;
use PoP\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver;

trait ValidateDoesLoggedInUserHaveCapabilityPublicSchemaTypeResolverDecoratorTrait
{
    /**
     * By default, only the admin can see the roles from the users
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    protected function getMandatoryDirectives($entryValue = null): array
    {
        $capabilities = $entryValue;
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $directiveResoverClass = $this->getValidateCapabilityDirectiveResolverClass();
        $directiveName = $directiveResoverClass::getDirectiveName();
        $validateDoesLoggedInUserHaveAnyCapabilityDirective = $fieldQueryInterpreter->getDirective(
            $directiveName,
            [
                'capabilities' => $capabilities,
            ]
        );
        return [
            $validateDoesLoggedInUserHaveAnyCapabilityDirective,
        ];
    }

    abstract protected function getValidateCapabilityDirectiveResolverClass(): string;
}
