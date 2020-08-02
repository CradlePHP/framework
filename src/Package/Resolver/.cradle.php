<?php //-->

use Cradle\Framework\Package\Resolver\ResolverPackage;

//map the package with the resolver package class methods
$this('resolver')->mapPackageMethods(new ResolverPackage);
