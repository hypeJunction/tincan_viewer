<?php

namespace TinCan\Viewer;

$guid = get_input('guid');
elgg_entity_gatekeeper($guid, 'object', Package::SUBTYPE);

$package = get_entity($guid);

elgg_push_breadcrumb(elgg_echo('tincan:all'), "tincan/all");

$container = $package->getContainerEntity();
elgg_set_page_owner_guid($container->guid);

if (elgg_instanceof($container, 'user')) {
	elgg_push_breadcrumb($container->getDisplayName(), "tincan/owner/$container->username");
} else if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb($container->getDisplayName(), "tincan/group/$container->guid");
}

elgg_push_breadcrumb($package->getDisplayName());

$title = $package->getDisplayName();
$content = elgg_view_entity($package, array(
	'full_view' => true,
		));

$layout = elgg_view_layout('one_column', array(
	'title' => $title,
	'content' => $content,
		));
echo elgg_view_page($title, $layout);
