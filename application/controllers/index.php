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
        if (is_logged_in()) {
            if (is_admin())
                redirect('/admin');
        }else {
            $this->load->view(THEME_PATH . 'default', $this->data);
        }
    }

}