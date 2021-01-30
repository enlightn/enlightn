<?php

declare(strict_types=1);

namespace Enlightn\Enlightn\PHPStan;

use PHPStan\Type\StringType;
use PHPStan\Type\Type;

class RequestDataType extends StringType
{
    /**
     * @param mixed[] $properties
     * @return Type
     */
    public static function __set_state(array $properties): Type
    {
        return new self();
    }
}
