<?php

/*
 * Wvern Index File
 * Codeigniter Wyvern Pixeljump.com.ph
 * 
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->view(ADMIN_THEME_PATH . 'index', $this->data);
    }

}