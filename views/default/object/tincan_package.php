<?php

namespace TinCan\Viewer;

use ElggEntity;

$entity = elgg_extract('entity', $vars);

if (!$entity instanceof ElggEntity) {
	return;
}

$owner = $entity->getOwnerEntity();
$full = elgg_extract('full_view', $vars, false);

$owner_link = elgg_view('output/url', array(
	'href' => "tincan/owner/$owner->username",
	'text' => $owner->getDisplayName(),
	'is_trusted' => true,
		));
$author_text = elgg_echo('byline', array($owner_link));

$date = elgg_view_friendly_time($file->time_created);

$categories = elgg_view('output/categories', $vars);

$subtitle = "$author_text $date $categories";

if ($full) {
	$title = false;
	$summary = '';
	$content = elgg_view('output/longtext', array(
		'value' => $entity->description,
	));
	$content .= elgg_format_element('iframe', array(
		'src' => $entity->getEmbedURL(),
		'seamless' => true,
		'frameborder' => 0,
		'scrolling' => auto,
		'width' => 1000,
		'height' => 700,
		
	));
	$metadata = elgg_view_menu('entity', array(
		'entity' => $entity,
		'handler' => 'tincan',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
	$tags = '';
} else {
	$title = '';
	$summary = elgg_get_excerpt($entity->description);
	$metadata = false;
	$tags = false;
}

$summary = elgg_view('object/elements/summary', array(
	'entity' => $entity,
	'title' => $title,
	'subtitle' => $subtitle,
	'content' => $summary,
	'metadata' => $metadata,
		));

$icon = elgg_view_entity_icon($entity, 'small');

if ($full) {
	echo elgg_view('object/elements/full', array(
		'entity' => $entity,
		'summary' => $summary,
		'icon' => $icon,
		'body' => $content,
		'class' => 'collections-resource-profile',
	));
} else {
	echo elgg_view_image_block($icon, $summary);
}
