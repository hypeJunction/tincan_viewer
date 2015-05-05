<?php

namespace TinCan\Viewer;

elgg_push_breadcrumb(elgg_echo('tincan:all'));

elgg_register_title_button();

$title = elgg_echo('tincan:all');
$content = elgg_list_entities(array(
	'types' =>'object',
	'subtypes' => Package::SUBTYPE,
	'container_guids' => ($container_guid) ?: ELGG_ENTITIES_ANY_VALUE,
));

$layout = elgg_view_layout('content', array(
	'title' => $title,
	'content' =>$content,
	'filter_context' => 'all',
));

echo elgg_view_page($title, $layout);