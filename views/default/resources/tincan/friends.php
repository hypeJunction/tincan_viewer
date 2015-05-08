<?php

namespace TinCan\Viewer;

$username = get_input('username');
$user = get_user_by_username($username);

$viewer = elgg_get_logged_in_user_entity();

if (!$user) {
	forward('', '404');
}

if (!$user->canEdit()) {
	register_error(elgg_echo('noaccess'));
	forward('tincan/all');
}

elgg_set_page_owner_guid($user->guid);

elgg_push_breadcrumb(elgg_echo('tincan:all'), 'tincan/all');
elgg_push_breadcrumb($user->getDisplayName(), "tincan/owner/$container->guid");
elgg_push_breadcrumb(elgg_echo('friends'), "tincan/friends/$container->guid");

if ($user->guid == $viewer->guid && $user->canWriteToContainer($viewer->guid, 'object', Package::SUBTYPE)) {
	elgg_register_title_button();
}

$title = elgg_echo('tincan:friends', array());
$content = elgg_list_entities_from_relationship(array(
	'types' =>'object',
	'subtypes' => Package::SUBTYPE,
	'relationship_guid' => $user->guid,
	'relationship_join_on' => 'owner_guid',
	'relationship' => 'friend',
));

$layout = elgg_view_layout('content', array(
	'title' => $title,
	'content' =>$content,
	'filter_context' => 'friends',
));

echo elgg_view_page($title, $layout);