<?php

namespace Enlightn\Enlightn\PHPStan;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Rules\Rule;
use PhpParser\Node;
use PHPStan\Analyser\Scope;

class MassAssignmentBuilderInstanceRule implements Rule
{
    use AnalyzesNodes;

    /**
     * @return string
     */
    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param Node $node
     * @param Scope $scope
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $node->name instanceof Node\Identifier
            || ! in_array($node->name->toString(), [
                'update', 'insert', 'upsert', 'insertOrIgnore', 'insertUsing', 'insertGetId', 'updateOrInsert',
            ])) {
            // Method name must match blacklisted names.
            return [];
        }

        if (! $this->isCalledOnBuilder($node->var, $scope)) {
            // Method was not called on a Builder, so no errors.
            return [];
        }

        if (isset($node->args[0]) && $this->retrievesRequestInput($node->args[0], $scope)) {
            return ["All request data should not be saved to the database. This may result in a mass assignment "
                ."vulnerability which overwrites database fields that were never intended to be modified. "
                ."Use the Request object's only or validated methods instead."];
        }

        return [];
    }

    /**
     * Determine whether the Arg was a request->all() method call.
     *
     * @param \PhpParser\Node\Arg $arg
     * @param \PHPStan\Analyser\Scope $scope
     * @return bool
     */
    protected function retrievesRequestInput(Node\Arg $arg, Scope $scope)
    {
        return $arg->value instanceof Node\Expr && $this->isRequestArrayData($arg->value, $scope);
    }
}
