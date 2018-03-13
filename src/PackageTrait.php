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
    protected $packages = [];

    /**
     * @var string $bootstrapFile A file to call on when a package is registered
     */
    protected $bootstrapFile = '.cradle';

    /**
     * Setups dispatcher and global package
     */
    public function __constructPackage()
    {
        //by default register a pseudo global package
        $this->register('global');
    }

    /**
     * Returns true if given is a registered package
     *
     * @param *string $vendor The vendor/package name
     *
     * @return bool
     */
    public function isPackage(string $vendor): bool
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
    public function package(string $vendor)
    {
        if (!array_key_exists($vendor, $this->packages)) {
            throw Exception::forPackageNotFound($vendor);
        }

        return $this->packages[$vendor]['package'];
    }

    /**
     * Registers and initializes a plugin
     *
     * @param *string $vendor  The vendor/package name
     * @param mixed   ...$args
     *
     * @return PackageTrait
     */
    public function register(string $vendor, ...$args)
    {
        //this is the package struct
        $package = [];

        //determine class
        if (method_exists($this, 'resolve')) {
            $package['package'] = $this->resolve(Package::class);
        // @codeCoverageIgnoreStart
        } else {
            $package['package'] = new Package;
        }
        // @codeCoverageIgnoreEnd

        //determine type
        //by default it's a pseudo package
        $package['type'] = 'pseudo';
        //if theres a slash like foo/bar or /foo/bar
        if (strpos($vendor, '/') !== false) {
            //it can be a vendor package
            $package['type'] = 'vendor';
            //if it starts with / like /foo/bar
            if (strpos($vendor, '/') === 0) {
                //it's a root package
                $package['type'] = 'root';
            }
        }

        //if the type is not pseudo (vendor or module)
        if ($package['type'] !== 'pseudo') {
            //determine where it is located
            //luckily we know where we are in vendor folder :)
            //is there a better recommended way?
            $package['root'] = __DIR__ . '/../../..';

            //the vendor name also represents the path
            $path = $vendor;

            //if it's a root package
            if ($package['type'] === 'root') {
                $package['root'] .= '/..';
                $path = substr($vendor, 1);
            }

            //either way set the root and path
            $package['root'] = realpath($package['root']);
            $package['path'] =  $package['root'] . '/' . $path;
        }

        //create a space
        $this->packages[$vendor] = $package;

        //if the type is not pseudo (vendor or module)
        if ($package['type'] !== 'pseudo') {
            //let's try to call the bootstrap
            $cradle = $this;

            //we should check for events
            $file = $package['path'] . '/' . $this->bootstrapFile;
            // @codeCoverageIgnoreStart
            if (file_exists($file)) {
                //so you can access cradle
                //within the included file
                include_once($file);
            } else if (file_exists($file . '.php')) {
                //so the IDE can have color
                include_once($file . '.php');
            }
            // @codeCoverageIgnoreEnd
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
    public function setBootstrapFile(string $file)
    {
        $this->bootstrapFile = $file;

        return $this;
    }
}
