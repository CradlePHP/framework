<?php //-->

use Cradle\Framework\Package\Host\HostPackage;

//map the package with the event package class methods
$this('host')->mapPackageMethods($this('resolver')->resolve(HostPackage::class));
