<?php

namespace TinCan\Viewer;

$username = get_input('username');
$user = get_user_by_username($username);

$viewer = elgg_get_logged_in_user_entity();

if (!$user) {
	forward('', '404');
}

elgg_set_page_owner_guid($user->guid);

elgg_push_breadcrumb(elgg_echo('tincan:all'), 'tincan/all');
elgg_push_breadcrumb($user->getDisplayName(), "tincan/owner/$user->guid");

if ($user->guid == $viewer->guid) {
	elgg_register_title_button();
}

$title = elgg_echo('tincan:all');
$content = elgg_list_entities(array(
	'types' =>'object',
	'subtypes' => Package::SUBTYPE,
	'owner_guids' => $user->guid,
));

$layout = elgg_view_layout('content', array(
	'title' => $title,
	'content' =>$content,
	'filter_context' => 'owner',
));

echo elgg_view_page($title, $layout);