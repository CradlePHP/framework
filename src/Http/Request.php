<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Http;

use Cradle\Data\Registry;

use Cradle\Http\Request\RequestInterface;

use Cradle\Http\Request\CliTrait;
use Cradle\Http\Request\ContentTrait;
use Cradle\Http\Request\CookieTrait;
use Cradle\Http\Request\FileTrait;
use Cradle\Http\Request\GetTrait;
use Cradle\Http\Request\RouteTrait;
use Cradle\Http\Request\PostTrait;
use Cradle\Http\Request\ServerTrait;
use Cradle\Http\Request\SessionTrait;
use Cradle\Http\Request\StageTrait;

/**
 * Http Request Object
 *
 * @vendor   Cradle
 * @package  Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Request extends Registry implements RequestInterface
{
    use CliTrait,
        ContentTrait,
        CookieTrait,
        FileTrait,
        GetTrait,
        PostTrait,
        RouteTrait,
        ServerTrait,
        SessionTrait,
        StageTrait;
    
    /**
     * Loads default data given by PHP
     *
     * @return Request
     */
    public function load()
    {
        global $argv;

        $this
            ->setArgs($argv)
            ->setContent(file_get_contents('php://input'));
        
        if (isset($_COOKIE)) {
            $this->setCookies($_COOKIE);
        }

        if (isset($_SESSION)) {
            $this->setSession($_SESSION);
        }

        if (isset($_GET)) {
            $this->setGet($_GET)->setStage($_GET);
        }

        if (isset($_POST)) {
            $this->setPost($_POST)->setStage($_POST);
        }

        if (isset($_FILES)) {
            $this->setFiles($_FILES);
        }

        if (isset($_SERVER)) {
            $this->setServer($_SERVER);
        }
        
        return $this;
    }
}
