<?php

namespace TinCan\Viewer;

$container_guid = get_input('container_guid');

elgg_group_gatekeeper(true, $container_guid);

$container = get_entity($container_guid);

elgg_set_page_owner_guid($container->guid);

elgg_push_breadcrumb(elgg_echo('tincan:all'), 'tincan/all');
elgg_push_breadcrumb($container->getDisplayName(), "tincan/group/$container->guid");

if ($user->guid == $viewer->guid) {
	elgg_register_title_button();
}

$title = elgg_echo('tincan:group');
$content = elgg_list_entities(array(
	'types' =>'object',
	'subtypes' => Package::SUBTYPE,
	'container_guids' => $container->guid,
));

$layout = elgg_view_layout('one_sidebar', array(
	'title' => $title,
	'content' =>$content,
));

echo elgg_view_page($title, $layout);