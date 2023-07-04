<?php declare(strict_types = 1);

namespace PHPStan\Type\BeberleiAssert;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;

class AssertionChainDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{

	public function getClass(): string
	{
		return 'Assert\AssertionChain';
	}

	public function isMethodSupported(MethodReflection $methodReflection): bool
	{
		return true;
	}

	public function getTypeFromMethodCall(
		MethodReflection $methodReflection,
		MethodCall $methodCall,
		Scope $scope
	): Type
	{
		$type = $scope->getType($methodCall->var);
		if (!$type instanceof AssertThatType) {
			return $type;
		}

		if ($methodReflection->getName() === 'all') {
			return $type->toAll();
		}

		if ($methodReflection->getName() === 'nullOr') {
			return $type->toNullOr();
		}

		return $type;
	}

}
