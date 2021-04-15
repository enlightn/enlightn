<?php

namespace Enlightn\Enlightn;

class CommitHash
{
    /**
     * @return string
     */
    public static function get()
    {
        return trim(exec('cd '.app_path().' && git log --pretty="%h" -n1 HEAD 2> /dev/null'));
    }
}
