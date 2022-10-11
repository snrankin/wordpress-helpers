<?php

/** ============================================================================
 * wp-add-google-tag-manager
 * ========================================================================== */


if (!defined('GTM_ID')) {
    define('GTM_ID', false);
}

if (!defined('GTM_ENV')) {
    define('GTM_ENV', false);
}

if (!defined('ENFORCE_GZIP')) {
    define('ENFORCE_GZIP', false);
}

function add_gtm_to_head() {

    $gtm_id = GTM_ID;

    $gtm_env = GTM_ENV;

    if (!empty($gtm_env)) {
        $gtm_env = " + '$gtm_env'";
    }

    $code = "<!-- Google Tag Manager -->\n<script>\n\t(function (w, d, s, l, i) {\n\t\tw[l] = w[l] || [];\n\t\tw[l].push({'gtm.start': new Date().getTime(), event: 'gtm.js'});\n\t\tvar f = d.getElementsByTagName(s)[0],\n\t\t\tj = d.createElement(s),\n\t\t\tdl = l != 'dataLayer'\n\t\t\t\t? '&l=' + l\n\t\t\t\t: '';\n\t\tj.async = true;\n\t\tj.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl$gtm_env;\n\t\tf.parentNode.insertBefore(j, f);\n\t})(window, document, 'script', 'dataLayer', 'GTM-$gtm_id');\n</script>\n<!-- End Google Tag Manager -->\n";

    if (ENFORCE_GZIP) {
        $code = preg_replace("/(\n|\t)*/m", '', $code);
    }

    echo wp_kses($code, array('script' => array()));
}

function add_gtm_to_body() {

    $gtm_id = GTM_ID;

    $gtm_env = GTM_ENV;

    $iframe_url = 'https://www.googletagmanager.com/ns.html?id=GTM-' . $gtm_id;

    if (!empty($gtm_env)) {
        $iframe_url .= $gtm_env;
    }

    $code = "<!-- Google Tag Manager -->\n<noscript>\n\t" . '<iframe src="' . $iframe_url . '" height="0" width="0" style="display:none;visibility:hidden"></iframe>' . "\n</noscript>\n<!-- End Google Tag Manager -->\n";

    if (ENFORCE_GZIP) {
        $code = preg_replace("/(\n|\t)*/m", '', $code);
    }

    echo wp_kses($code, array('noscript' => array(), 'iframe' => array('src' => true, 'height' => true, 'width' => true, 'style' => true)));
}

if (!empty(GTM_ID)) {

    add_action('wp_head', 'add_gtm_to_head', 9999);

    add_action('wp_body_open', 'add_gtm_to_body');
}
