<?php //-->

use Cradle\Framework\Config\ConfigPackage;

//map the package with the event package class methods
$this('config')->mapPackageMethods($this('resolver')->resolve(ConfigPackage::class));
