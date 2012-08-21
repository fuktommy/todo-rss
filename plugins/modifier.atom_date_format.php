<?php
require_once $smarty->_get_plugin_filepath('shared', 'make_timestamp');

/**
 * Atom date format.
 *
 * This plugin come from Smarty date_format modifier plugin.
 */
function smarty_modifier_atom_date_format($string)
{
    if ($string != '') {
        $timestamp = smarty_make_timestamp($string);
    } elseif ($default_date != '') {
        $timestamp = smarty_make_timestamp($default_date);
    } else {
        return;
    }
    return gmstrftime('%Y-%m-%dT%H:%M:%SZ', $timestamp);
}
