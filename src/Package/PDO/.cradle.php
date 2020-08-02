<?php //-->

use Cradle\Framework\Package\PDO\PDOPackage;

//map the package with the event package class methods
$this('pdo')->mapPackageMethods($this('resolver')->resolve(PDOPackage::class, $this));
