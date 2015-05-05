<?php

namespace TinCan\Viewer;

require_once __DIR__ . '/vendor/autoload.php';

$types = array(
	'object' => array(
		Package::SUBTYPE => get_class(new Package),
	),
);

foreach ($types as $type => $subtypes) {
	foreach ($subtypes as $subtype => $class) {
		if (!update_subtype($type, $subtype, $class)) {
			add_subtype($type, $subtype, $class);
		}
	}
}