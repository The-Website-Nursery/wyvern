<?php

/*
 * Wvern Layout Helper
 * Codeigniter Wyvern Pixeljump.com.ph
 * 
 */

/* Theme Helper Files */

function get_header() {
    get_ci()->load->view(THEME_PATH . 'partials/header');
}

function get_footer() {
    get_ci()->load->view(THEME_PATH . 'partials/footer');
}

function get_modal() {
    get_ci()->load->view(THEME_PATH . 'partials/modals/default');
}

function get_asset_url($assets = array()) {
    if (is_array($assets)) {
        $assets_url = array();

        foreach ($assets as $asset) {
            $assets_url[] = base_url() . ASSETS_PATH . $asset;
        }

        return $assets_url;
    } else {
        return array(base_url() . ASSETS_PATH . $asset);
    }
}

function get_single_asset_url($asset = '') {
    return base_url() . ASSETS_PATH . $asset;
}