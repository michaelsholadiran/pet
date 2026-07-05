<?php
/**
 * State/region lists by country. Used for checkout and any form that needs state dropdown.
 * Nigeria only — we deliver to Lagos.
 */
if (!defined('PUPPIARY_STATES_LOADED')) {
    define('PUPPIARY_STATES_LOADED', true);
}

// Nigeria: only Lagos available
$STATES_NG = ['Lagos'];

// US (and other non-NG): only California available
// $STATES_US = ['California'];
