<?php

namespace Enlightn\Enlightn\Tests\Stubs;

class InvalidOffsetStub
{
    public function foo()
    {
        $value = 'Foo';
        $value['foo'] = null;

        $obj1 = new ObjectWithOffsetAccess();
        $obj1[false] = 'invalid key, valid value';

        /** @var array|int $value */
        $value = [];
        $value['foo'] += null;
    }

    /**
     * @param \ArrayAccess<int,int> $arrayAccess
     */
    public function bar(\ArrayAccess $arrayAccess)
    {
        $arrayAccess[] = 'foo';
    }
}

class ObjectWithOffsetAccess implements \ArrayAccess
{
    /**
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return true;
    }

    /**
     * @param string $offset
     * @return int
     */
    public function offsetGet($offset)
    {
        return 0;
    }

    /**
     * @param string $offset
     * @param int $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
    }

    /**
     * @param string $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
    }
}
