<?php //-->
/**
 * This file is part of the Cradle PHP Command Line
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Cradle\Framework\CommandLine;

use Cradle\Framework\CommandLine;
use Cradle\Framework\Exception;
use Cradle\Framework\Decorator;

use Cradle\Event\EventHandler;

use Cradle\Http\Request;
use Cradle\Http\Response;

//enable the function
Decorator::DECORATE;

/**
 * Uninstall CLI Command
 *
 * @vendor   Scoop
 * @package  Framework
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Event
{
    /**
     * @const string ERROR_ARGUMENT_COUNT
     */
    const ERROR_ARGUMENT_COUNT = 'Not enough arguments. Usage: cradle event name';

    /**
     * @const string ERROR_BOOTSTRAP_FLAG
     */
    const ERROR_BOOTSTRAP_FLAG = 'Could not determine bootstrap file. Try --bootrap=path/to/.cradle.php';

    /**
     * @const string ERROR_BOOTSTRAP_404
     */
    const ERROR_BOOTSTRAP_404 = 'Bootstrap path %s not found';

    /**
     * @const string SUCCESS_MESSAGE
     */
    const SUCCESS_MESSAGE = '%s has successfully completed.';

    /**
     * @var string|null $cwd The path from where this was called
     */
    protected $cwd = null;

    /**
     * We need the CWD and the Schema
     *
     * @param string $cwd The path from where this was called
     */
    public function __construct($cwd)
    {
        $this->cwd = $cwd;
    }

    /**
     * Runs the CLI Generate process
     *
     * @param array $args CLI arguments
     *
     * @return void
     */
    public function run($args)
    {
        //expecting cradle install cradle/address
        // @codeCoverageIgnoreStart
        if (count($args) < 2) {
            CommandLine::error(self::ERROR_ARGUMENT_COUNT);
        }
        // @codeCoverageIgnoreEnd

        //parse the data
        $data = CommandLine::parseArgs(array_slice($args, 2));

        $bootstrap = $this->cwd . '/.cradle.php';

        //if no bootstrap
        if (!file_exists($bootstrap)) {
            //look for bootstrap=./path/to/bootstrap.php
            //look for bootstrap=path/to/bootstrap.php
            //look for bootstrap=/path/to/bootstrap.php
            // @codeCoverageIgnoreStart
            if (!isset($data['bootstrap'])) {
                CommandLine::error(self::ERROR_BOOTSTRAP_FLAG);
            }
            // @codeCoverageIgnoreEnd

            $bootstrap = $data['bootstrap'];

            if (strpos($bootstrap, './') === 0) {
                $bootstrap = substr($bootstrap, 2);
            }

            if (strpos($bootstrap, '/') !== 0) {
                $bootstrap = $this->cwd . '/' . $bootstrap;
            }

            // @codeCoverageIgnoreStart
            if (!file_exists($bootstrap)) {
                CommandLine::error(sprintf(self::ERROR_BOOTSTRAP_404, $bootstrap));
            }
            // @codeCoverageIgnoreEnd
        }

        $cradle = cradle();
        require_once($bootstrap);

        //Setup a default error handler
        // @codeCoverageIgnoreStart
        $cradle->error(function ($request, $response, $error) {
            CommandLine::error($error->getMessage() . PHP_EOL . $error->getTraceAsString());
        });
        // @codeCoverageIgnoreEnd

        //prepare data
        $event = $args[1];

        if (isset($data['__json'])) {
            $json = $data['__json'];
            unset($data['__json']);

            $data = array_merge(json_decode($json, true), $data);
        }

        if (isset($data['__json64'])) {
            $base64 = $data['__json64'];
            unset($data['__json64']);

            $json = base64_decode($base64);
            $data = array_merge(json_decode($json, true), $data);
        }

        if (isset($data['__query'])) {
            $query = $data['__query'];
            unset($data['__query']);

            parse_str($query, $query);

            $data = array_merge($query, $data);
        }

        //set the the request and response
        $request = $cradle->getRequest();
        $response = $cradle->getResponse();

        $request->setStage($data);

        //see HttpTrait->render() for similar implementation
        //if prepared returned false
        if (!$cradle->prepare()) {
            //can't test this without invoking an exception in the test somehow
            //that implies writing more code just to cover a test...
            // @codeCoverageIgnoreStart
            //dont do anything else
            return $this;
            // @codeCoverageIgnoreEnd
        }

        if ($response->getStatus() !== EventHandler::STATUS_OK) {
            CommandLine::error(sprintf('Aborting %s because of a preprocessor call aborted.', $event));
        }

        $continue = $cradle
            ->trigger($event, $request, $response)
            ->getEventHandler()
            ->getMeta();

        //if incomplete
        if ($continue === EventHandler::STATUS_INCOMPLETE) {
            //same rationale as above
            // @codeCoverageIgnoreStart
            CommandLine::error(sprintf('Aborting %s because of an event call aborted.', $event));
            // @codeCoverageIgnoreEnd
        }

        //if incomplete
        if ($continue === EventHandler::STATUS_NOT_FOUND) {
            //same rationale as above
            // @codeCoverageIgnoreStart
            CommandLine::error(sprintf('No event %s was triggered.', $event));
            // @codeCoverageIgnoreEnd
        }

        if (!$response->hasContent() && $response->hasJson()) {
            $json = json_encode($response->get('json'));
            $response->setContent($json);
        }

        if ($response->hasContent()) {
            CommandLine::output($response->getContent());
        } else {
            CommandLine::info(sprintf(self::SUCCESS_MESSAGE, $args[1]));
        }

        //the connection is already closed
        //also remember there are no more sessions
        $cradle->shutdown();
    }
}
