<?php //-->

use Cradle\I18n\Timezone;

//map the package with the event package class methods
$this('tz')->mapPackageMethods($this('resolver')->resolve(Timezone::class, 'GMT'));
