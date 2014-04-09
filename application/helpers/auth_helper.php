<?php

/*
 * Wvern Authentication Helper
 * Codeigniter Wyvern Pixeljump.com.ph
 * 
 */

function is_logged_in() {
    return (get_user_detail('identity'));
}

function get_user_detail($detail = null) {
    return get_ci()->session->userdata($detail);
}

function get_user_level() {
    $groups_results = get_ci()->ion_auth->get_users_groups(get_ci()->session->userdata('user_id'))->result_array();

    $groups_array = array();

    foreach ($groups_results as $result) {
        $groups_array[] = $result['name'];
    }

    return $groups_array;
}

/* Check for User Group */

function is_admin() {
    return in_array('admin', get_user_level());
}

function is_member() {
    return in_array('members', get_user_level());
}

function is_client() {
    return in_array('clients', get_user_level());
}

function is_manager() {
    return in_array('managers', get_user_level());
}
