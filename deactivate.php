<?php

namespace TinCan\Viewer;

$types = array(
	'object' => array(
		Package::SUBTYPE => get_class(new Package),
	),
);

foreach ($types as $type => $subtypes) {
	foreach ($subtypes as $subtype => $class) {
		update_subtype($type, $subtype);
	}
}
