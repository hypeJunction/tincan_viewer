<?php

namespace TinCan\Viewer;

if (elgg_is_sticky_form('tincan/edit')) {
	$sticky = elgg_get_sticky_values('tincan/edit');
	if (is_array($sticky)) {
		$vars = array_merge($vars, $sticky);
	}
}

echo elgg_view_form('tincan/edit', array(
	'enctype' => 'multipart/form-data',
		), $vars);
