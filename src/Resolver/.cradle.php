<?php //-->

use Cradle\Framework\Resolver\ResolverPackage;

//map the package with the resolver package class methods
$this('resolver')->mapPackageMethods(new ResolverPackage);
