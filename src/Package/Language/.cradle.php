<?php //-->

use Cradle\I18n\Language;

//map the package with the event package class methods
$this('lang')->mapPackageMethods($this('resolver')->resolve(Language::class));
