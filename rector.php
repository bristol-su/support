<?php

declare(strict_types=1);

use BristolSU\CodeStyle\Rector\Rules;
use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\CodeQuality\Rector\Ternary\ArrayKeyExistsTernaryThenValueToCoalescingRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Rector\CodeQuality\Rector\Assign\CombinedAssignRector;
use Rector\CodeQuality\Rector\ClassMethod\DateTimeToDateTimeInterfaceRector;
use Rector\CodeQuality\Rector\FuncCall\IntvalToTypeCastRector;
use Rector\CodeQuality\Rector\Identical\GetClassToInstanceOfRector;
use Rector\CodeQuality\Rector\If_\ConsecutiveNullCompareReturnsToNullCoalesceQueueRector;
use Rector\CodeQuality\Rector\Name\FixClassCaseSensitivityNameRector;
use Rector\CodeQuality\Rector\New_\NewStaticToNewSelfRector;
use Rector\CodeQuality\Rector\Return_\SimplifyUselessVariableRector;
use Rector\CodingStyle\Rector\ClassConst\SplitGroupedConstantsAndPropertiesRector;
use Rector\CodingStyle\Rector\ClassConst\VarConstantCommentRector;
use Rector\CodingStyle\Rector\ClassMethod\MakeInheritedMethodVisibilitySameAsParentRector;
use Rector\CodingStyle\Rector\Encapsed\WrapEncapsedVariableInCurlyBracesRector;
use Rector\CodingStyle\Rector\Throw_\AnnotateThrowablesRector;
use Rector\CodingStyle\Rector\Use_\RemoveUnusedAliasRector;
use Rector\CodingStyle\Rector\Use_\SplitGroupedUseImportsRector;
use Rector\DeadCode\Rector\ClassConst\RemoveUnusedPrivateConstantRector as RemoveUnusedPrivateConstantRectorAlias;
use Rector\DeadCode\Rector\ClassMethod\RemoveDeadConstructorRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedParameterRector;
use Rector\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector;
use Rector\DeadCode\Rector\Function_\RemoveUnusedFunctionRector;
use Rector\DeadCode\Rector\FunctionLike\RemoveDeadReturnRector;
use Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector;
use Rector\Naming\Rector\Property\UnderscoreToCamelCasePropertyNameRector;
use Rector\Naming\Rector\Variable\UnderscoreToCamelCaseLocalVariableNameRector;
use Rector\Naming\Rector\Variable\UnderscoreToCamelCaseVariableNameRector;
use Rector\PHPUnit\Rector\Class_\AddProphecyTraitRector;
use Rector\PSR4\Rector\Namespace_\MultipleClassFileToPsr4ClassesRector;

return static function (ContainerConfigurator $containerConfigurator): void {

    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PATHS, [
        __DIR__ . '/src'
    ]);
    $services = $containerConfigurator->services();

    $rules = new Rules();
//    $services = $containerConfigurator->services();
    $rules->setRules($services);
};
