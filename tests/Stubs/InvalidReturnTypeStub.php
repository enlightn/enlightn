<?php

namespace Enlightn\Enlightn\Tests\Stubs;

class InvalidReturnTypeStub
{
    public function returnInteger(): int
    {
        if (rand(0, 1)) {
            return 1;
        }

        if (rand(0, 1)) {
            return 'foo';
        }
    }

    /**
     * @return void
     */
    public function returnVoid()
    {
        if (rand(0, 1)) {
            return;
        }

        if (rand(0, 1)) {
            return null;
        }

        if (rand(0, 1)) {
            return 1;
        }
    }
}

function returnInteger(): int
{
    if (rand(0, 1)) {
        return 1;
    }

    if (rand(0, 1)) {
        return 'foo';
    }
}

/**
 * @return void
 */
function returnVoid()
{
    if (rand(0, 1)) {
        return;
    }

    if (rand(0, 1)) {
        return null;
    }

    if (rand(0, 1)) {
        return 1;
    }
}
