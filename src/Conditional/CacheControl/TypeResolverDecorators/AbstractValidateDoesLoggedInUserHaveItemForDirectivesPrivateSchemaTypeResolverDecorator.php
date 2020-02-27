<?php
namespace PoP\UserRolesAccessControl\Conditional\CacheControl\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\CacheControl\DirectiveResolvers\AbstractCacheControlDirectiveResolver;
use PoP\AccessControl\TypeResolverDecorators\AbstractPrivateSchemaTypeResolverDecorator;
use PoP\UserStateAccessControl\TypeResolverDecorators\ValidateConditionForDirectivesPublicSchemaTypeResolverDecoratorTrait;

abstract class AbstractValidateDoesLoggedInUserHaveItemForDirectivesPrivateSchemaTypeResolverDecorator extends AbstractPrivateSchemaTypeResolverDecorator
{
    use ValidateConditionForDirectivesPublicSchemaTypeResolverDecoratorTrait;

    abstract protected function getEntryList(): array;

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
        $entryList = $this->getEntryList();
        $noCacheControlDirectiveResolver = $fieldQueryInterpreter->getDirective(
            AbstractCacheControlDirectiveResolver::getDirectiveName(),
            [
                'maxAge' => 0,
            ]
        );
        foreach ($entryList as $entry) {
            $directiveResolverClass = $entry[0];
            $mandatoryDirectivesForDirectives[$directiveResolverClass::getDirectiveName()] = [
                $noCacheControlDirectiveResolver,
            ];
        }
        return $mandatoryDirectivesForDirectives;
    }
}
