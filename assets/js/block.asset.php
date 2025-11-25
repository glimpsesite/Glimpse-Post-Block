<?php
return array(
	'dependencies' => array(
		'wp-blocks',
		'wp-element',
		'wp-i18n',
		'wp-components',
		'wp-block-editor',
		'wp-server-side-render'
	),
	'version' => '1a2b3c4d5e6f',
);

wp_enqueue_script(
  'glimpse-post-block-editor',
  GLIMPSE_POST_BLOCK_PLUGIN_URL . 'assets/js/block.js',
  array(
    'wp-blocks',
    'wp-element',
    'wp-editor',
    'wp-components',
    'wp-i18n',
    'wp-server-side-render' // ensure this is present
  ),
  GLIMPSE_POST_BLOCK_VERSION,
  true
);