<?php
/* itv_writing_prompt */
global $prefix;
$prefix = 'itv_writing_prompt';
global $meta_box;
global $num;
$num = 1;
$meta_box = array (
	'id' 		=>	$prefix . '_id',
	'title'		=>	'Writing Prompt',
	'page'		=>	'post',
	'context'	=>	'side',
	'priority'	=>	'high',
	'fields'	=>	array(
		array (
			'name'		=>	'Thesis',
			'desc'		=>	'Your thesis should be your main point. What are you trying to prove or say?',
			'id'		=>	$prefix . '_textarea',
			'type'		=>	'thesis',
			'add'		=>	false,
			'std'		=>	''
		),
		array(
			'name'		=>	'Type',
			'desc'		=>	'Is this expository, persuasive, explanatory...?',
			'id'		=>	$prefix . '_type_of_post',
			'type'		=>	'posttype',
			'add'		=>	false,
			'std'		=>	''
		),
		array(
			'name'		=>	'Primary Argument/Statement',
			'desc'		=>	'What\'s your strongest argument or statement?',
			'id'		=>	$prefix . '_primary_argument',
			'type'		=>	'primary_argument',
			'add'		=>	false,
			'std'		=>	''
		),
		array(
			'name'		=>	'Supporting Argument/Statements',
			'desc'		=>	'What are your supporting thoughts? Have more than one?',
			'id'		=>	$prefix . '_supporting_argument',
			'type'		=>	'supporting_argument',
			'add'		=>	false,
			'std'		=>	''
		),
		array(
			'name'		=>	'Conclusion',
			'desc'		=>	'How are you going to wrap the whole thing up?',
			'id'		=>	$prefix . '_conclusion',
			'type'		=>	'conclusion',
			'add'		=>	false,
			'std'		=>	''
		),
		array(
			'name'		=>	'Sources',
			'desc'		=>	'Don\'t forget to credit other people for what they\'ve created!',
			'id'		=>	$prefix . '_sources',
			'type'		=>	'source',
			'add'		=>	false,
			'std'		=>	''
		)
	)
); 
// Add meta box
function itv_writing_prompt_add_box() {
	global $meta_box;
	add_meta_box($meta_box['id'], $meta_box['title'], 'itv_writing_prompt_show_box', $meta_box['page'], $meta_box['context'], $meta_box['priority']);
}
add_action('add_meta_boxes', $prefix.'_add_box');

// Callback function to show fields in meta box

function itv_writing_prompt_show_box() {
	global $meta_box, $post, $prefix;
	// Use nonce for verification
	wp_nonce_field( $prefix.'_verify_nonce', $prefix.'_nonce' );
	echo '<div class="itv_wp_forms">';
	foreach ($meta_box['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		if (!$field['add'] == true) {
			echo '<div class="itv_wp_row">',
				'<label class="itv_wp_label" for="', $field['id'], '">', $field['name'], '</label><span class="itv_wp_tooltip_link">?</span><span class="itv_wp_tooltip">', $field['desc'],'</span>',
				'<div>'; 
		}/* else {
			echo '<div class="itv_wp_row">',
				'<label class="itv_wp_label" for="', $field['id'], '">', $field['name'], '</label><span class="itv_wp_tooltip_link">?</span><span class="itv_wp_tooltip">', $field['desc'],'</span><span class="itv_wp_add">+</span>',
				'<div>';
		}*/
		switch ($field['type']) {
			case 'thesis':
				echo '<textarea name="', $field['id'], '" id="', $field['id'], '">', $meta ? $meta : $field['std'], '</textarea>';
				break;
			case 'posttype':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '"/>';
				break;
			case 'primary_argument':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '"/>';
				break;
			case 'supporting_argument':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '"/>';
				break;
			case 'conclusion':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '"/>';
				break;
			case 'source':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '"/>';
				break;
		}
		echo     '</div>',
			'</div>';
	}
	echo '</div>';
}
// Save data from meta box
function itv_writing_prompt_save($post_id) {
	global $meta_box, $prefix;
	// verify nonce
	if (!isset($_POST[$prefix.'_nonce']) || !wp_verify_nonce($_POST[$prefix . '_nonce'], $prefix.'_verify_nonce')) {
		return $post_id;
	}
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
	foreach ($meta_box['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}
add_action('save_post', $prefix . '_save');
?>