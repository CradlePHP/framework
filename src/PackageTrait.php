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
    public function register(string $vendor, ...$args)
    {
        //determine class
        if (method_exists($this, 'resolve')) {
            $this->packages[$vendor] = $this->resolve(Package::class, $vendor);
        // @codeCoverageIgnoreStart
        } else {
            $this->packages[$vendor] = new Package($vendor);
        }
        // @codeCoverageIgnoreEnd

        //if the type is not pseudo (vendor or root)
        if ($this->packages[$vendor]->getPackageType() !== Package::TYPE_PSEUDO) {
            //let's try to call the bootstrap
            $cradle = $this;

            //we should check for events
            $file = $this->packages[$vendor]->getPackagePath() . '/' . $this->bootstrapFile;

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
