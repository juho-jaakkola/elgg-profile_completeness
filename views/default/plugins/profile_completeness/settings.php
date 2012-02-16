<?php

$helper = new ProfileCompletenessHelper;

$fieldstypes = $helper->getFields();

$body = '<br />';
$body .= '<label>' . elgg_echo('profile_completeness:enabled_fields') . '</label><br />';

$fields = array();
foreach($fieldstypes as $field => $type) {
    $body .= "<span style=\"display:inline-block;width:200px;\">" . elgg_echo("profile:$field") . "</span>";
    
    $fieldname = "enable_{$field}";
    
    $body .= elgg_view('input/dropdown',
        array(
            'name' => "params[$fieldname]",
            'options_values' => array(false => '', true => 'Enabled'),
            'value' => $vars['entity']->$fieldname,
        ));
    $body .= '<br />';
}

$body .= '<br /><br />';

$body .= '<label>' . elgg_echo('profile_completeness:tip_amount') . '</label>';
$body .= elgg_view('input/text',
    array(
        'name' => "params[tip_amount]",
        'value' => $vars['entity']->tip_amount,
    ));

$body .= '<br /><br />';

echo $body;