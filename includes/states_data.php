<?php
/**
 * State/region lists by country. Used for checkout and any form that needs state dropdown.
 * Nigeria = NG (CURRENCY_IS_NGN) — we only deliver to Lagos. US/others — only California.
 */
if (!defined('PUPPIARY_STATES_LOADED')) {
    define('PUPPIARY_STATES_LOADED', true);
}

// Nigeria: only Lagos available
$STATES_NG = ['Lagos'];

// US (and other non-NG): only California available
$STATES_US = ['California'];
