<?php
/**
 * Place ACF JSON in field-groups directory.
 * Save fields as JSON, allow syncing.
 */
add_filter('acf/settings/save_json', function ($path) {
    return dirname(__FILE__) . '/field-groups';
});

add_filter('acf/settings/load_json', function ($paths) {
    unset($paths[0]);
    $paths[] = dirname(__FILE__) . '/field-groups';

    return $paths;
});
