<?php

/**
 * Elgg profile completeness plugin
 * 
 * @package ProfileCompleteness
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Juho Jaakkola
 * @copyright (C) 2011, 2012 Mediamaisteri Group
 * @link http://www.mediamaisteri.com/
 */

function profile_completeness_init () {
	$path = elgg_get_plugins_path() . 'profile_completeness/classes/ProfileCompletenessHelper.php';
    elgg_register_library('elgg:profile_completeness', $path);
    
    // Add css
    elgg_extend_view('css', 'profile_completeness/css');
    
	// @todo Is there really need for a widget?
    //elgg_register_widget_type('profile_completeness', elgg_echo('profile_completeness:widget:title'), elgg_echo('profile_completeness:widget:description'));
}

elgg_register_event_handler('init','system','profile_completeness_init');
