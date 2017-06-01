<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Http\Response;

/**
 * Designed for the Response Object; Adds methods to process REST type responses
 *
 * @vendor   Cradle
 * @package  Server
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait RestTrait
{
    /**
     * Adds a JSON validation message or sets all the validations
     *
     * @param *string $field
     * @param *string $message
     *
     * @return RestTrait
     */
    public function addValidation(string $field, string $message)
    {
        $args = func_get_args();

        return $this->set('json', 'validation', ...$args);
    }

    /**
     * Returns JSON results if still in array mode
     *
     * @param mixed ...$args
     *
     * @return mixed
     */
    public function getResults(...$args)
    {
        if (!count($args)) {
            return $this->getDot('json.results');
        }

        return $this->get('json', 'results', ...$args);
    }

    /**
     * Returns the message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->getDot('json.message');
    }

    /**
     * Determines the message type based on error
     *
     * @return string
     */
    public function getMessageType(): string
    {
        $error = $this->get('json', 'error');

        if ($error === true) {
            return 'error';
        }

        if ($error === false) {
            return 'success';
        }

        return 'info';
    }

    /**
     * Returns JSON validations if still in array mode
     *
     * @param string|null $name
     * @param mixed       ...$args
     *
     * @return mixed
     */
    public function getValidation(string $name = null, ...$args)
    {
        if (is_null($name)) {
            return $this->getDot('json.validation');
        }

        return $this->get('json', 'validation', $name, ...$args);
    }

    /**
     * Returns true if there's any JSON
     *
     * @param mixed ...$args
     *
     * @return bool
     */
    public function hasJson(...$args): bool
    {
        if (!count($args)) {
            return $this->exists('json');
        }

        return $this->exists('json', ...$args);
    }

    /**
     * Returns true if there's a message
     *
     * @return bool
     */
    public function hasMessage(): bool
    {
        return $this->hasJson('message');
    }

    /**
     * Returns true if there's any results given name
     *
     * @param mixed ...$args
     *
     * @return bool
     */
    public function hasResults(...$args): bool
    {
        return $this->hasJson('results', ...$args);
    }

    /**
     * Returns true if there's any validations given name
     *
     * @param mixed ...$args
     *
     * @return bool
     */
    public function hasValidation(...$args): bool
    {
        return $this->hasJson('validation', ...$args);
    }

    /**
     * Returns true if there's an error
     *
     * @return bool
     */
    public function isError(): bool
    {
        return $this->get('json', 'error') === true;
    }

    /**
     * Returns true if there's no error
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->get('json', 'error') === false;
    }

    /**
     * Removes results given name or all of the results
     *
     * @param *string $name Name of the validation
     *
     * @return RestTrait
     */
    public function removeResults(string $name)
    {
        $args = func_get_args();
        return $this->remove('json', 'results', ...$args);
    }

    /**
     * Removes a validation given name or all the validations
     *
     * @param *string $name Name of the validation
     *
     * @return RestTrait
     */
    public function removeValidation(string $name)
    {
        $args = func_get_args();
        return $this->remove('json', 'validation', ...$args);
    }

    /**
     * Sets a JSON error message
     *
     * @param *bool|null  $status  True if there is an error
     * @param string|null $message A message to describe this error
     *
     * @return RestTrait
     */
    public function setError($status, string $message = null)
    {
        $this->setDot('json.error', $status);

        if (!is_null($message)) {
            $this->setDot('json.message', $message);
        }

        return $this;
    }

    /**
     * Sets a JSON result
     *
     * @param *mixed $data
     * @param mixed  ...$args
     *
     * @return RestTrait
     */
    public function setResults($data, ...$args)
    {
        if (is_array($data)) {
            return $this->setDot('json.results', $data);
        }

        return $this->set('json', 'results', $data, ...$args);
    }
}
