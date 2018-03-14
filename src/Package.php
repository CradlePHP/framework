<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Framework;

use Closure;

/**
 * Package space for package methods
 *
 * @vendor   Cradle
 * @package  Framework
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Package
{
    /**
     * @const string NO_METHOD Error template
     */
    const NO_METHOD = 'No method named %s was found';

    /**
     * @const string TYPE_PSEUDO
     */
    const TYPE_PSEUDO = 'pseudo';

    /**
     * @const string TYPE_ROOT
     */
    const TYPE_ROOT = 'root';

    /**
     * @const string TYPE_VENDOR
     */
    const TYPE_VENDOR = 'vendor';

    /**
     * @var string $name
     */
    protected $name = null;

    /**
     * @var array $methods A list of virtual methods
     */
    protected $methods = array();

    /**
     * Store the name so we can profile later
     *
     * @param *string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * When a method doesn't exist, it this will try to call one
     * of the virtual methods.
     *
     * @param *string $name name of method
     * @param *array  $args arguments to pass
     *
     * @return mixed
     */
    public function __call(string $name, array $args)
    {
        if (isset($this->methods[$name])) {
            return call_user_func_array($this->methods[$name], $args);
        }

        throw Exception::forMethodNotFound($name);
    }

    /**
     * Registers a method to be used
     *
     * @param *string  $name     The class route name
     * @param *Closure $callback The callback handler
     *
     * @return Package
     */
    public function addMethod(string $name, Closure $callback): Package
    {
        $this->methods[$name] = $callback->bindTo($this, get_class($this));

        return $this;
    }

    /**
     * Returns the path of the project
     *
     * @return string|false
     */
    public function getPackagePath()
    {
        $type = $this->getPackageType();
        if ($type === self::TYPE_PSEUDO) {
            return false;
        }

        $root = $this->getPackageRoot();

        //the vendor name also represents the path
        $path = $this->name;

        //if it's a root package
        if ($type === self::TYPE_ROOT) {
            $path = substr($path, 1);
        }

        return $root . '/' . $path;
    }

    /**
     * Returns the root path of the project
     *
     * @return string|false
     */
    public function getPackageRoot()
    {
        $type = $this->getPackageType();
        if ($type === self::TYPE_PSEUDO) {
            return false;
        }

        //determine where it is located
        //luckily we know where we are in vendor folder :)
        //is there a better recommended way?
        $root = __DIR__ . '/../../..';

        //if it's a root package
        if ($type === self::TYPE_ROOT) {
            $root .= '/..';
        }

        return realpath($root);
    }

    /**
     * Returns the package type
     *
     * @return string
     */
    public function getPackageType(): string
    {
        //if it starts with / like /foo/bar
        if (strpos($this->name, '/') === 0) {
            //it's a root package
            return self::TYPE_ROOT;
        }

        //if theres a slash like foo/bar
        if (strpos($this->name, '/') !== false) {
            //it's vendor package
            return self::TYPE_VENDOR;
        }

        //by default it's a pseudo package
        return self::TYPE_PSEUDO;
    }

    /**
     * Either returns the current available version
     * or the next version
     *
     * @param bool $next
     *
     * @return string
     */
    public function getPackageVersion(bool $next = false): string
    {
        $path = $this->getPackagePath();

        if ($path === false) {
            return '0.0.0';
        }

        //sorry there's no way I can test this without it being a real package.
        // @codeCoverageIgnoreStart
        $path .= '/install';

        if (!is_dir($path)) {
            return '0.0.0';
        }

        //collect and organize all the versions
        $versions = [];
        $files = scandir($path, 0);
        foreach ($files as $file) {
            if ($file === '.'
                || $file === '..'
                || is_dir($path . '/' . $file)
            ) {
                continue;
            }

            //get extension
            $extension = pathinfo($file, PATHINFO_EXTENSION);

            if ($extension !== 'php'
                && $extension !== 'sh'
                && $extension !== 'sql'
            ) {
                continue;
            }

            //get base as version
            $version = pathinfo($file, PATHINFO_FILENAME);

            //validate version
            if (!(version_compare($version, '0.0.1', '>=') >= 0)) {
                continue;
            }

            $versions[] = $version;
        }

        if (empty($versions)) {
            return '0.0.0';
        }

        //sort versions
        usort($versions, 'version_compare');

        $version = array_pop($versions);

        if (!$next) {
            return $version;
        }

        $revisions = explode('.', $version);
        $revisions = array_reverse($revisions);

        $found = false;
        foreach ($revisions as $i => $revision) {
            if (!is_numeric($revision)) {
                continue;
            }

            $revisions[$i]++;
            $found = true;
            break;
        }

        if (!$found) {
            return $current . '.1';
        }

        $revisions = array_reverse($revisions);
        return implode('.', $revisions);
        // @codeCoverageIgnoreEnd
    }
}
