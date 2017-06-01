<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Http\Request;

/**
 * Designed for the Request Object; Adds methods to store $_REQUEST data
 *
 * @vendor   Cradle
 * @package  Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait StageTrait
{
    /**
     * Returns $_REQUEST given name or all $_REQUEST
     *
     * @param mixed ...$args
     *
     * @return mixed
     */
    public function getStage(...$args)
    {
        return $this->get('stage', ...$args);
    }

    /**
     * Returns true if has $_REQUEST given name or if $_REQUEST is set
     *
     * @param mixed ...$args
     *
     * @return bool
     */
    public function hasStage(...$args): bool
    {
        return $this->exists('stage', ...$args);
    }

    /**
     * Removes $_REQUEST given name or all $_REQUEST
     *
     * @param mixed ...$args
     *
     * @return StageTrait
     */
    public function removeStage(...$args)
    {
        return $this->remove('stage', ...$args);
    }

    /**
     * Clusters request data together softly
     *
     * @param *array $data
     *
     * @return StageTrait
     */
    public function setSoftStage(array $data)
    {
        //one dimenstions soft setter
        foreach ($data as $key => $value) {
            if ($this->exists('stage', $key)) {
                continue;
            }

            $this->set('stage', $key, $value);
        }

        return $this;
    }

    /**
     * Sets $_POST
     *
     * @param *mixed $data
     * @param mixed  ...$args
     *
     * @return StageTrait
     */
    public function setStage($data, ...$args)
    {
        if (is_array($data)) {
            //one dimenstions soft setter
            foreach ($data as $key => $value) {
                $this->set('stage', $key, $value);
            }

            return $this;
        }

        if (count($args) === 0) {
            return $this;
        }

        return $this->set('stage', $data, ...$args);
    }
}
