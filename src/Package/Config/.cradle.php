<?php //-->

use Cradle\Framework\Package\Config\ConfigPackage;

//map the package with the event package class methods
$this('config')->mapPackageMethods($this('resolver')->resolve(ConfigPackage::class));
