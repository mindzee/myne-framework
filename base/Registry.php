<?php

namespace myne\base;

/**
 *
 */
abstract class Registry
{
    protected abstract function _get($key);
    protected abstract function _set($key, $value);
}
