<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Framework;

/**
 * If you want to utilize composer packages
 * as plugins/extensions/addons you can adopt
 * this trait
 *
 * @vendor   Cradle
 * @package  Framework
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait PackageTrait
{
    /**
     * @var array $packages A safe place to store package junk
     */
    protected $packages = array();

    /**
     * @var string $bootstrapFile A file to call on when a package is registered
     */
    protected $bootstrapFile = '.cradle';

    /**
     * Setups dispatcher and global package
     */
    public function __constructPackage()
    {
        if (method_exists($this, 'resolve')) {
            $this->packages['global'] = $this->resolve(Package::class);
        } else {
            $this->packages['global'] = new Package;
        }
    }

    /**
     * Returns true if given is a registered package
     *
     * @param *string $vendor The vendor/package name
     *
     * @return bool
     */
    public function isPackage($vendor)
    {
        return isset($this->packages[$vendor]);
    }

    /**
     * Returns a package space
     *
     * @param *string $vendor The vendor/package name
     *
     * @return PackageTrait
     */
    public function package($vendor)
    {
        if (!isset($this->packages[$vendor])) {
            throw Exception::forPackageNotFound($vendor);
        }

        return $this->packages[$vendor];
    }

    /**
     * Registers and initializes a plugin
     *
     * @param *string $vendor  The vendor/package name
     * @param mixed   ...$args
     *
     * @return PackageTrait
     */
    public function register($vendor, ...$args)
    {
        //create a space
        if (method_exists($this, 'resolve')) {
            $this->packages[$vendor] = $this->resolve(Package::class);
        } else {
            $this->packages[$vendor] = new Package;
        }

        //luckily we know where we are in vendor folder :)
        //is there a better recommended way?
        $root = __DIR__ . '/../../..';

        if (strpos($vendor, '/') === 0) {
            $root .= '/..';
            $vendor = substr($vendor, 1);
        }

        $cradle = $this;

        //we should check for events
        $file = $root . '/' . $vendor . '/' . $this->bootstrapFile;
        if (file_exists($file)) {
            //so you can access cradle
            //within the included file
            include_once($file);
        } else if (file_exists($file . '.php')) {
            //so the IDE can have color
            include_once($file . '.php');
        }

        return $this;
    }

    /**
     * Returns a package space
     *
     * @param *string $file A file to call on when a package is registered
     *
     * @return PackageTrait
     */
    public function setBootstrapFile($file)
    {
        $this->bootstrapFile = $file;

        return $this;
    }
}
