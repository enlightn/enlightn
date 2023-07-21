<?php

namespace Enlightn\Enlightn\Tests\Stubs;

class DeprecatedCodeStub
{
    public function deprecatedClass()
    {
        Foo::$deprecatedProp = 5;
        Foo::depStatFoo();

        $foo = new Foo();
        $foo->deprecatedFoo = 2;
        $foo->depFoo();

        $depFoo = new DeprecatedFoo();
        $foo->foo = 2;
        depFunction();

        $fooable = new class implements DeprecatedFooable {

		};
    }
}

class Foo
{
    public $foo;

    /**
     * @deprecated
     */
    public $deprecatedFoo;

    /**
     * @deprecated
     */
    public static $deprecatedProp;

    /**
	 * @deprecated
	 */
	public function depFoo()
	{
	}

    /**
	 * @deprecated another comment
	 */
	public static function depStatFoo()
	{
	}
}

/**
 * @deprecated some comment
 */
class DeprecatedFoo
{
    public $foo;
}

/**
 * @deprecated
 */
function depFunction()
{
}

/**
 * @deprecated
 */
interface DeprecatedFooable
{
}

class Foo2 implements DeprecatedFooable
{
}

/**
 * @deprecated desc
 */
trait DeprecatedFooTrait
{
}

class Foo3
{
	use DeprecatedFooTrait;
}
