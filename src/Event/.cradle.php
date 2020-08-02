<?php //-->

use Cradle\Framework\Event\EventPackage;

$this('event')
  //map the package with the event package class methods
  ->mapPackageMethods($this('resolver')->resolve(EventPackage::class, $this))
  //use one global resolver
  ->setResolverHandler($this('resolver')->getResolverHandler());
