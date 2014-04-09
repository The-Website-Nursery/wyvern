<?php

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->__init();
    }

    function __init() {
        $this->load->config('wyvern');

        define('THEME_PATH', $this->config->item('theme_path') . $this->config->item('theme') . "/");
        define('ASSETS_PATH', $this->config->item('assets_path') . $this->config->item('theme') . "/");

        $this->data['theme_logo'] = ASSETS_PATH . 'images/' . $this->config->item('logo_default');

        $this->data['js'] = get_asset_url($this->config->item('theme_js'));
        $this->data['css'] = get_asset_url($this->config->item('theme_css'));
        $this->data['fonts'] = get_asset_url($this->config->item('theme_fonts'));

        /* Load Modules */
        $this->load->spark('ion_auth/2.5.0'); 
    }

}
