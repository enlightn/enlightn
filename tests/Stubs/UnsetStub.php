<?php

namespace Enlightn\Enlightn\Tests\Stubs;

class UnsetStub
{
    public function unsetInaccessible ()
    {
        unset($notSetVariable);
        unset($notSetVariable['a']);

        $scalar = 3;
        unset($scalar['a']);

        $singleDimArray = ['a' => 1];
        unset($singleDimArray['a']['b']);

        $multiDimArray = ['a' => ['b' => 1]];
        unset($multiDimArray['a']['b']['c'], $scalar, $singleDimArray['a']['b']);
    }
}
