<?php

namespace Enlightn\Enlightn\PHPStan;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Rules\Rule;
use PhpParser\Node;
use PHPStan\Analyser\Scope;

class MassAssignmentModelStaticRule implements Rule
{
    use AnalyzesNodes;

    /**
     * @return string
     */
    public function getNodeType(): string
    {
        return StaticCall::class;
    }

    /**
     * @param Node $node
     * @param Scope $scope
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $node->name instanceof Node\Identifier) {
            return [];
        }

        if ($node->class instanceof Node\Name
            && ! is_subclass_of($scope->resolveName($node->class), Model::class)) {
            // We are only looking at static calls on a Model class
            return [];
        }

        if ($node->class instanceof Node\Expr && ! $this->isCalledOn($node->class, $scope, Model::class)) {
            // We are only looking at static calls on a Model class
            return [];
        }

        if (! in_array($node->name->toString(), [
            'create', 'forceCreate', 'firstOrCreate', 'updateOrCreate', 'insert', 'upsert',
            'update', 'insertOrIgnore', 'make', 'firstOrNew',
        ])) {
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
