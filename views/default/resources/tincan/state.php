<?php

namespace TinCan\Viewer;

elgg_gatekeeper();

$guid = get_input('guid');
elgg_entity_gatekeeper($guid, 'object', Package::SUBTYPE);

$user = elgg_get_logged_in_user_entity();
$package = get_entity($guid);
/* @var $package Package */

$payload = file_get_contents('php://input');
if (is_string($payload)) {
	parse_str(urldecode($payload), $data);
} else {
	$data = (array) $payload;
}


$key = get_site_secret();
$hmac = elgg_extract('hmac', $data);

if ($hmac === hash_hmac('sha256', $package->package_id . $guid . $_COOKIE['Elgg'], $key)) {
	header('Content-Type: appication/json');
	if (get_input('method') == 'PUT') {
		echo json_encode($package->saveState($user, $data));
	} else {
		echo json_encode($package->retrieveState($user, $data));
	}
	exit;
} else {
	header('HTTP/1.1 403 Forbidden');
	exit;
}