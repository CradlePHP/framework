<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Framework\Package\Config;

use Cradle\Data\Registry;

/**
 * Config Package
 *
 * @vendor   Cradle
 * @package  Package
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class ConfigPackage
{
  /**
   * @var string $path
   */
  protected $path;

  /**
   * @var Registry $registry
   */
  protected $registry;

  /**
   * Set the registry
   */
  public function __construct()
  {
    $this->registry = Registry::i();
  }

  /**
   * Returns true if the file+path exists
   *
   * @param *string  $file
   * @param string[] $path
   *
   * @return bool
   */
  public function exists(string $file, ...$path): bool
  {
    if (!is_dir($this->path)) {
      throw ConfigException::forFolderNotSet();
    }

    $source = sprintf('%s/%s.php', $this->path, $file);

    //make sure we have the data from the file
    if (!$this->registry->exists($file) && file_exists($source)) {
      $this->registry->set($file, include $source);
    }

    if (empty($path)) {
      return $this->registry->exists($file);
    }

    //return the registry
    return $this->registry->exists($file, ...$path);
  }

  /**
   * Sets the origin where all configs are located
   *
   * @param *string $pathh whether to load the RnRs
   *
   * @return mixed
   */
  public function get(string $file, ...$path)
  {
    if (!is_dir($this->path)) {
      throw ConfigException::forFolderNotSet();
    }

    $source = sprintf('%s/%s.php', $this->path, $file);

    //make sure we have the data from the file
    if (!$this->exists($file) && file_exists($source)) {
      $this->registry->set($file, include $source);
    }

    if (empty($path)) {
      return $this->registry->get($file);
    }

    //return the registry
    return $this->registry->get($file, ...$path);
  }

  /**
   * Returns the origin where all configs are located
   *
   * @return ?string
   */
  public function getFolder(string $extra = null): ?string
  {
    if ($extra) {
      if (strpos($extra, '/') !== 0) {
        $extra = '/' . $extra;
      }

      return $this->path . $extra;
    }

    return $this->path;
  }

  /**
   * Sets the origin where all configs are located
   *
   * @param *string $pathh whether to load the RnRs
   *
   * @return ConfigPackage
   */
  public function set(string $file, ...$path): ConfigPackage
  {
    if (!is_dir($this->path)) {
      throw ConfigException::forFolderNotSet();
    }

    if (empty($path)) {
      return $this;
    }

    $destination = sprintf('%s/%s.php', $this->path, $file);

    //make sure we have the data from the file
    if (!$this->exists($file) && file_exists($destination)) {
      $this->registry->set($file, include $destination);
    }

    //set the registry
    $this->registry->set($file, ...$path);

    if (!is_dir(dirname($destination))) {
      mkdir(dirname($destination), 0777, true);
    }

    //save the file
    file_put_contents($destination, sprintf(
      "<?php //-->\nreturn %s;",
      var_export($this->registry->get($file), true)
    ));

    return $this;
  }

  /**
   * Sets the origin where all configs are located
   *
   * @param *string $path whether to load the RnRs
   *
   * @return ConfigPackage
   */
  public function setFolder(string $path): ConfigPackage
  {
    if (!is_dir($path)) {
      throw ConfigException::forFolderNotFound($path);
    }

    $this->path = $path;
    return $this;
  }
}
