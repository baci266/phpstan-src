<?php declare(strict_types = 1);

namespace PHPStan\Analyser;

use PhpParser\Node\Expr;
use PHPStan\Broker\Broker;
use PHPStan\DependencyInjection\Container;
use PHPStan\Reflection\ParametersAcceptor;

class ScopeFactory
{

	/** @var string */
	private $scopeClass;

	/** @var \PHPStan\Broker\Broker */
	private $broker;

	/** @var \PhpParser\PrettyPrinter\Standard */
	private $printer;

	/** @var \PHPStan\Analyser\TypeSpecifier */
	private $typeSpecifier;

	/** @var string[] */
	private $dynamicConstantNames;

	public function __construct(
		string $scopeClass,
		Broker $broker,
		\PhpParser\PrettyPrinter\Standard $printer,
		TypeSpecifier $typeSpecifier,
		Container $container
	)
	{
		$this->scopeClass = $scopeClass;
		$this->broker = $broker;
		$this->printer = $printer;
		$this->typeSpecifier = $typeSpecifier;
		$this->dynamicConstantNames = $container->getParameter('dynamicConstantNames');
	}

	/**
	 * @param \PHPStan\Analyser\ScopeContext $context
	 * @param bool $declareStrictTypes
	 * @param \PHPStan\Reflection\FunctionReflection|\PHPStan\Reflection\MethodReflection|null $function
	 * @param string|null $namespace
	 * @param \PHPStan\Analyser\VariableTypeHolder[] $variablesTypes
	 * @param \PHPStan\Analyser\VariableTypeHolder[] $moreSpecificTypes
	 * @param string|null $inClosureBindScopeClass
	 * @param \PHPStan\Reflection\ParametersAcceptor|null $anonymousFunctionReflection
	 * @param \PhpParser\Node\Expr\FuncCall|\PhpParser\Node\Expr\MethodCall|\PhpParser\Node\Expr\StaticCall|null $inFunctionCall
	 * @param bool $negated
	 * @param bool $inFirstLevelStatement
	 * @param array<string, true> $currentlyAssignedExpressions
	 *
	 * @return Scope
	 */
	public function create(
		ScopeContext $context,
		bool $declareStrictTypes = false,
		$function = null,
		?string $namespace = null,
		array $variablesTypes = [],
		array $moreSpecificTypes = [],
		?string $inClosureBindScopeClass = null,
		?ParametersAcceptor $anonymousFunctionReflection = null,
		?Expr $inFunctionCall = null,
		bool $negated = false,
		bool $inFirstLevelStatement = true,
		array $currentlyAssignedExpressions = []
	): Scope
	{
		$scopeClass = $this->scopeClass;
		if (!is_a($scopeClass, Scope::class, true)) {
			throw new \PHPStan\ShouldNotHappenException();
		}

		return new $scopeClass(
			$this,
			$this->broker,
			$this->printer,
			$this->typeSpecifier,
			$context,
			$declareStrictTypes,
			$function,
			$namespace,
			$variablesTypes,
			$moreSpecificTypes,
			$inClosureBindScopeClass,
			$anonymousFunctionReflection,
			$inFunctionCall,
			$negated,
			$inFirstLevelStatement,
			$currentlyAssignedExpressions,
			$this->dynamicConstantNames
		);
	}

}
