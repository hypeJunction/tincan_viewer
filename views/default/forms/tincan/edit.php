<?php

namespace TinCan\Viewer;

$entity = elgg_extract('entity', $vars);
$container = (elgg_instanceof($entity)) ? $entity->getContainerEntity() : elgg_extract('container', $vars);
?>
<div class="row required">
	<label><?php echo elgg_echo('tincan:edit:title') ?></label>
	<?php
	echo elgg_view('input/text', array(
		'name' => 'title',
		'value' => elgg_extract('title', $vars, $entity->title),
		'required' => true,
	));
	?>
</div>
<div class="row">
	<label><?php echo elgg_echo('tincan:edit:file') ?></label>
	<?php
	if (!$entity) {
		echo elgg_view('input/file', array(
			'name' => 'package',
		));
	} else {
		echo elgg_format_element('p', array(
			'class' => 'elgg-text-help',
				), elgg_echo('tincan:edit:file:uploaded'));
	}
	?>
</div>
<div class="row">
	<label><?php echo elgg_echo('tincan:edit:description') ?></label>
	<?php
	echo elgg_view('input/longtext', array(
		'name' => 'description',
		'value' => elgg_extract('description', $vars, $entity->title),
	))
	?>
</div>
<?php
echo elgg_view('input/category', $vars);
?>
<div class="row">
	<label><?php echo elgg_echo('tincan:edit:tags') ?></label>
	<?php
	echo elgg_view('input/tags', array(
		'name' => 'tags',
		'value' => elgg_extract('tags', $vars, $entity->tags),
		'entity' => $entity,
	))
	?>
</div>
<div class="row">
	<label><?php echo elgg_echo('tincan:edit:access_id') ?></label>
	<?php
	echo elgg_view('input/access', array(
		'name' => 'access_id',
		'value' => elgg_extract('access_id', $vars, ($entity) ? $entity->access_id : get_default_access()),
	))
	?>
</div>

<div class="elgg-foot row text-right">
	<?php
	echo elgg_view('input/hidden', array(
		'name' => 'guid',
		'value' => $entity->guid,
	));
	echo elgg_view('input/hidden', array(
		'name' => 'container_guid',
		'value' => $container->guid,
	));
	echo elgg_view('input/submit', array(
		'value' => elgg_echo('save'),
	));
	?>
</div>
