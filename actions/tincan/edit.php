<?php

namespace TinCan\Viewer;

use Exception;

elgg_make_sticky_form('tincan/edit');

$title = get_input('title');
$description = get_input('description');
$tags = string_to_tag_array(get_input('tags', ''));
$access_id = get_input('access_id');

if (!$title) {
	register_error(elgg_echo('tincan:edit:missingfield'));
	forward(REFERER);
}


$guid = get_input('guid');
if ($guid) {
	$package = get_entity($guid);
} else {
	$container_guid = get_input('container_guid');
	if (!$container_guid) {
		$container_guid = elgg_get_logged_in_user_guid();
	}

	$container = get_entity($container_guid);
	if (!$container || !$container->canWriteToContainer(0, 'object', Package::SUBTYPE)) {
		register_error(elgg_echo('tincan:edit:noaccess'));
		forward(REFERER);
	}

	$package = new Package();
	$package->owner_guid = elgg_get_logged_in_user_guid();
	$package->container_guid = $container->guid;
}

$package->title = $title;
$package->description = $description;
$package->tags = $tags;
$package->access_id = $access_id;

try {
	if (!$package->save()) {
		throw new Exception(egg_echo('tincan:edit:error:save'));
	}

	if (!empty($_FILES['package']['name']) && $_FILES['package']['error'] == UPLOAD_ERR_OK) {

		$name = $_FILES['icon']['name'];
		$package->originalfilename = $name;

		$filehandler = $package->getSourceFile();
		$filehandler->open('write');
		$filehandler->close();

		move_uploaded_file($_FILES['package']['tmp_name'], $filehandler->getFilenameOnFilestore());

		$package->unzip();

		$details = $package->getManifest()->getPackageDetails();

		$package->package_name = elgg_extract('name', $details);
		$package->package_description = elgg_extract('description', $details);
		$package->package_id = elgg_extract('id', $details);
		$package->package_type = elgg_extract('type', $details);
		$package->package_launch = elgg_extract('launch', $details);

		$package->package_time = time();
	}

	elgg_clear_sticky_form('tincan/edit');

	forward($package->getURL());
} catch (Exception $ex) {
	$package->delete();

	register_error(elgg_echo('tincan:edit:error', array($ex->getMessage())));
	forward(REFERER);
}
