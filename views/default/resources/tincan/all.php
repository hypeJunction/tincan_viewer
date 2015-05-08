<?php

namespace TinCan\Viewer;

elgg_push_breadcrumb(elgg_echo('tincan:all'));

$user = elgg_get_logged_in_user_entity();
if ($user && $user->canWriteToContainer(0, 'object', Package::SUBTYPE)) {
	elgg_register_title_button();
}

$title = elgg_echo('tincan:all');
$content = elgg_list_entities(array(
	'types' =>'object',
	'subtypes' => Package::SUBTYPE,
));

$layout = elgg_view_layout('content', array(
	'title' => $title,
	'content' =>$content,
	'filter_context' => 'all',
));

echo elgg_view_page($title, $layout);