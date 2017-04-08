<?php

/**
 * @package Dbmover
 * @subpackage Conditionals
 *
 * Gather all conditionals and optionally wrap them in a "lambda".
 */

namespace Dbmover\Conditionals;

use Dbmover\Core;

class Plugin extends Core\Plugin
{
    public function __invoke(string $sql) : string
    {
        if (preg_match_all('@^IF.*?^END IF;$@ms', $sql, $ifs, PREG_SET_ORDER)) {
            foreach ($ifs as $if) {
                $tmp = 'tmp_'.md5(microtime(true));
                $code = $this->wrap($if[0]);
                $this->defer($code, $if[0]);
                $this->loader->addOperation($code, $if[0]);
                $sql = str_replace($if[0], '', $sql);
            }
        }
        return $sql;
    }

    protected function wrap(string $sql) : string
    {
        return $sql;
    }
}

