<?php

namespace TinCan\Viewer;

$guid = get_input('guid');
elgg_entity_gatekeeper($guid, 'object', Package::SUBTYPE);

$entity = get_entity($guid);
$container = $entity->getContainerEntity();

elgg_set_page_owner_guid($container->guid);

$title = elgg_echo('tincan:edit');

elgg_push_breadcrumb(elgg_echo('tincan:all'), 'tincan/all');
if ($container instanceof \ElggGroup) {
	elgg_push_breadcrumb($container->getDisplayName(), "tincan/group/$container->guid");
} else {
	elgg_push_breadcrumb($container->getDisplayName(), "tincan/owner/$container->guid");
}
elgg_push_breadcrumb($entity->getDisplayName(), $entity->getURL());
elgg_push_breadcrumb($title);

$content = elgg_view('tincan/edit', array(
	'entity' => $entity,
));

$layout = elgg_view_layout('one_sidebar', array(
	'title' => $title,
	'content' => $content,
));

echo elgg_view_page($title, $layout);

