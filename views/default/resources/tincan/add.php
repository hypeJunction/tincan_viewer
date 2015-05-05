<?php

namespace TinCan\Viewer;

$container_guid = get_input('container_guid');
if (!$container_guid) {
	$container_guid = elgg_get_logged_in_user_guid();
}

$container = get_entity($container_guid);

if (!$container || !$container->canWriteToContainer(0, 'object', Package::SUBTYPE)) {
	register_error(elgg_echo('noaccess'));
	forward(REFERER);
}

elgg_set_page_owner_guid($container->guid);

$title = elgg_echo('tincan:add');

elgg_push_breadcrumb(elgg_echo('tincan:all'), 'tincan/all');
elgg_push_breadcrumb($container->getDisplayName(), "tincan/all/$container->guid");
elgg_push_breadcrumb($title);

$content = elgg_view('tincan/edit', array(
	'container' => $container,
));

$layout = elgg_view_layout('one_sidebar', array(
	'title' => $title,
	'content' => $content,
));

echo elgg_view_page($title, $layout);

