<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Data;

/**
 * Given that there's $data this will setup magic methods.
 * By default these methods are suffixed with `Data` to
 * prevent collisions. You need to alias them on implementation.
 *
 * @package  Cradle
 * @category Data
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait MagicTrait
{

    /**
     * Processes get and set type methods
     *
     * @param *string $name Name of method
     * @param *array  $args Arguments to pass
     *
     * @return mixed
     */
    public function __callData(string $name, array $args)
    {
        //if the method starts with get
        if (strpos($name, 'get') === 0) {
            //getUserName('-')
            $separator = '_';
            if (isset($args[0]) && is_scalar($args[0])) {
                $separator = (string) $args[0];
            }

            $key = preg_replace("/([A-Z0-9])/", $separator."$1", $name);
            //get rid of get
            $key = strtolower(substr($key, 3 + strlen($separator)));

            return $this->__getData($key);
        } else if (strpos($name, 'set') === 0) {
            //setUserName('Chris', '-')
            $separator = '_';
            if (isset($args[1]) && is_scalar($args[1])) {
                $separator = (string) $args[1];
            }

            $key = preg_replace("/([A-Z0-9])/", $separator."$1", $name);

            //get rid of set
            $key = strtolower(substr($key, 3 + strlen($separator)));

            $this->__setData($key, isset($args[0]) ? $args[0] : null);

            return $this;
        }

        throw DataException::forMethodNotFound(get_class($this), $name);
    }

    /**
     * Allow object property magic to redirect to the data variable
     *
     * @param *string $name  The name of the supposed property
     * @param *mixed  $value The value of the supposed property
     */
    public function __getData(string $name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        return null;
    }

    /**
     * Allow object property magic to redirect to the data variable
     *
     * @param *string $name  The name of the supposed property
     * @param *mixed  $value The value of the supposed property
     */
    public function __setData(string $name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * If we output this to string we should see it as json
     *
     * @return string
     */
    public function __toStringData(): string
    {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
