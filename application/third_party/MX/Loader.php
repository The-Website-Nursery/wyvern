<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link	http://codeigniter.com
 *
 * Description:
 * This library extends the CodeIgniter CI_Loader class
 * and adds features allowing use of modules and the HMVC design pattern.
 *
 * Install this file as application/third_party/MX/Loader.php
 *
 * @copyright	Copyright (c) 2011 Wiredesignz
 * @version 	5.4
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * */
class MX_Loader extends CI_Loader {

    protected $_module;
    public $_ci_plugins = array();
    public $_ci_cached_vars = array();

    /** Initialize the loader variables * */
    public function initialize($controller = NULL) {

        /* set the module name */
        $this->_module = CI::$APP->router->fetch_module();

        if (is_a($controller, 'MX_Controller')) {

            /* reference to the module controller */
            $this->controller = $controller;

            /* references to ci loader variables */
            foreach (get_class_vars('CI_Loader') as $var => $val) {
                if ($var != '_ci_ob_level') {
                    $this->$var = & CI::$APP->load->$var;
                }
            }
        } else {
            parent::initialize();

            /* autoload module items */
            $this->_autoloader(array());
        }

        /* add this module path to the loader variables */
        $this->_add_module_paths($this->_module);
    }

    /** Add a module path loader variables * */
    public function _add_module_paths($module = '') {

        if (empty($module))
            return;

        foreach (Modules::$locations as $location => $offset) {

            /* only add a module path if it exists */
            if (is_dir($module_path = $location . $module . '/') && !in_array($module_path, $this->_ci_model_paths)) {
                array_unshift($this->_ci_model_paths, $module_path);
            }
        }
    }

    /** Load a module config file * */
    public function config($file = 'config', $use_sections = FALSE, $fail_gracefully = FALSE) {
        return CI::$APP->config->load($file, $use_sections, $fail_gracefully, $this->_module);
    }

    /** Load the database drivers * */
    public function database($params = '', $return = FALSE, $active_record = NULL) {

        if (class_exists('CI_DB', FALSE) AND $return == FALSE AND $active_record == NULL AND isset(CI::$APP->db) AND is_object(CI::$APP->db))
            return;

        require_once BASEPATH . 'database/DB' . EXT;

        if ($return === TRUE)
            return DB($params, $active_record);

        CI::$APP->db = DB($params, $active_record);

        return CI::$APP->db;
    }

    /** Load a module helper * */
    public function helper($helper = array()) {

        if (is_array($helper))
            return $this->helpers($helper);

        if (isset($this->_ci_helpers[$helper]))
            return;

        list($path, $_helper) = Modules::find($helper . '_helper', $this->_module, 'helpers/');

        if ($path === FALSE)
            return parent::helper($helper);

        Modules::load_file($_helper, $path);
        $this->_ci_helpers[$_helper] = TRUE;
    }

    /** Load an array of helpers * */
    public function helpers($helpers = array()) {
        foreach ($helpers as $_helper)
            $this->helper($_helper);
    }

    /** Load a module language file * */
    public function language($langfile = array(), $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '') {
        return CI::$APP->lang->load($langfile, $idiom, $return, $add_suffix, $alt_path, $this->_module);
    }

    public function languages($languages) {
        foreach ($languages as $_language)
            $this->language($_language);
    }

    /** Load a module library * */
    public function library($library = '', $params = NULL, $object_name = NULL) {

        if (is_array($library))
            return $this->libraries($library);

        $class = strtolower(basename($library));

        if (isset($this->_ci_classes[$class]) AND $_alias = $this->_ci_classes[$class])
            return CI::$APP->$_alias;

        ($_alias = strtolower($object_name)) OR $_alias = $class;

        list($path, $_library) = Modules::find($library, $this->_module, 'libraries/');

        /* load library config file as params */
        if ($params == NULL) {
            list($path2, $file) = Modules::find($_alias, $this->_module, 'config/');
            ($path2) AND $params = Modules::load_file($file, $path2, 'config');
        }

        if ($path === FALSE) {

            $this->_ci_load_class($library, $params, $object_name);
            $_alias = $this->_ci_classes[$class];
        } else {

            Modules::load_file($_library, $path);

            $library = ucfirst($_library);
            CI::$APP->$_alias = new $library($params);

            $this->_ci_classes[$class] = $_alias;
        }

        return CI::$APP->$_alias;
    }

    /** Load an array of libraries * */
    public function libraries($libraries) {
        foreach ($libraries as $_library)
            $this->library($_library);
    }

    /** Load a module model * */
    public function model($model, $object_name = NULL, $connect = FALSE) {

        if (is_array($model))
            return $this->models($model);

        ($_alias = $object_name) OR $_alias = basename($model);

        if (in_array($_alias, $this->_ci_models, TRUE))
            return CI::$APP->$_alias;

        /* check module */
        list($path, $_model) = Modules::find(strtolower($model), $this->_module, 'models/');

        if ($path == FALSE) {

            /* check application & packages */
            parent::model($model, $object_name, $connect);
        } else {

            class_exists('CI_Model', FALSE) OR load_class('Model', 'core');

            if ($connect !== FALSE AND !class_exists('CI_DB', FALSE)) {
                if ($connect === TRUE)
                    $connect = '';
                $this->database($connect, FALSE, TRUE);
            }

            Modules::load_file($_model, $path);

            $model = ucfirst($_model);
            CI::$APP->$_alias = new $model();

            $this->_ci_models[] = $_alias;
        }

        return CI::$APP->$_alias;
    }

    /** Load an array of models * */
    public function models($models) {
        foreach ($models as $_model)
            $this->model($_model);
    }

    /** Load a module controller * */
    public function module($module, $params = NULL) {

        if (is_array($module))
            return $this->modules($module);

        $_alias = strtolower(basename($module));
        CI::$APP->$_alias = Modules::load(array($module => $params));
        return CI::$APP->$_alias;
    }

    /** Load an array of controllers * */
    public function modules($modules) {
        foreach ($modules as $_module)
            $this->module($_module);
    }

    /** Load a module plugin * */
    public function plugin($plugin) {

        if (is_array($plugin))
            return $this->plugins($plugin);

        if (isset($this->_ci_plugins[$plugin]))
            return;

        list($path, $_plugin) = Modules::find($plugin . '_pi', $this->_module, 'plugins/');

        if ($path === FALSE AND !is_file($_plugin = APPPATH . 'plugins/' . $_plugin . EXT)) {
            show_error("Unable to locate the plugin file: {$_plugin}");
        }

        Modules::load_file($_plugin, $path);
        $this->_ci_plugins[$plugin] = TRUE;
    }

    /** Load an array of plugins * */
    public function plugins($plugins) {
        foreach ($plugins as $_plugin)
            $this->plugin($_plugin);
    }

    /** Load a module view * */
    public function view($view, $vars = array(), $return = FALSE) {
        list($path, $_view) = Modules::find($view, $this->_module, 'views/');

        if ($path != FALSE) {
            $this->_ci_view_paths = array($path => TRUE) + $this->_ci_view_paths;
            $view = $_view;
        }

        return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
    }

    public function _ci_is_instance() {
        
    }

    protected function &_ci_get_component($component) {
        return CI::$APP->$component;
    }

    public function __get($class) {
        return (isset($this->controller)) ? $this->controller->$class : CI::$APP->$class;
    }

    public function _ci_load($_ci_data) {

        extract($_ci_data);

        if (isset($_ci_view)) {

            $_ci_path = '';

            /* add file extension if not provided */
            $_ci_file = (pathinfo($_ci_view, PATHINFO_EXTENSION)) ? $_ci_view : $_ci_view . EXT;

            foreach ($this->_ci_view_paths as $path => $cascade) {
                if (file_exists($view = $path . $_ci_file)) {
                    $_ci_path = $view;
                    break;
                }

                if (!$cascade)
                    break;
            }
        } elseif (isset($_ci_path)) {

            $_ci_file = basename($_ci_path);
            if (!file_exists($_ci_path))
                $_ci_path = '';
        }

        if (empty($_ci_path))
            show_error('Unable to load the requested file: ' . $_ci_file);

        if (isset($_ci_vars))
            $this->_ci_cached_vars = array_merge($this->_ci_cached_vars, (array) $_ci_vars);

        extract($this->_ci_cached_vars);

        ob_start();

        if ((bool) @ini_get('short_open_tag') === FALSE AND CI::$APP->config->item('rewrite_short_tags') == TRUE) {
            echo eval('?>' . preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($_ci_path))));
        } else {
            include($_ci_path);
        }

        log_message('debug', 'File loaded: ' . $_ci_path);

        if ($_ci_return == TRUE)
            return ob_get_clean();

        if (ob_get_level() > $this->_ci_ob_level + 1) {
            ob_end_flush();
        } else {
            CI::$APP->output->append_output(ob_get_clean());
        }
    }

    /** Autoload module items * */
    public function _autoloader($autoload) {

        $path = FALSE;

        if ($this->_module) {

            list($path, $file) = Modules::find('constants', $this->_module, 'config/');

            /* module constants file */
            if ($path != FALSE) {
                include_once $path . $file . EXT;
            }

            list($path, $file) = Modules::find('autoload', $this->_module, 'config/');

            /* module autoload file */
            if ($path != FALSE) {
                $autoload = array_merge(Modules::load_file($file, $path, 'autoload'), $autoload);
            }
        }

        /* nothing to do */
        if (count($autoload) == 0)
            return;

        /* autoload package paths */
        if (isset($autoload['packages'])) {
            foreach ($autoload['packages'] as $package_path) {
                $this->add_package_path($package_path);
            }
        }

        /* autoload config */
        if (isset($autoload['config'])) {
            foreach ($autoload['config'] as $config) {
                $this->config($config);
            }
        }

        /* autoload helpers, plugins, languages */
        foreach (array('helper', 'plugin', 'language') as $type) {
            if (isset($autoload[$type])) {
                foreach ($autoload[$type] as $item) {
                    $this->$type($item);
                }
            }
        }

        /* autoload database & libraries */
        if (isset($autoload['libraries'])) {
            if (in_array('database', $autoload['libraries'])) {
                /* autoload database */
                if (!$db = CI::$APP->config->item('database')) {
                    $db['params'] = 'default';
                    $db['active_record'] = TRUE;
                }
                $this->database($db['params'], FALSE, $db['active_record']);
                $autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
            }

            /* autoload libraries */
            foreach ($autoload['libraries'] as $library) {
                $this->library($library);
            }
        }

        /* autoload models */
        if (isset($autoload['model'])) {
            foreach ($autoload['model'] as $model => $alias) {
                (is_numeric($model)) ? $this->model($alias) : $this->model($model, $alias);
            }
        }

        /* autoload module controllers */
        if (isset($autoload['modules'])) {
            foreach ($autoload['modules'] as $controller) {
                ($controller != $this->_module) AND $this->module($controller);
            }
        }
    }

    private $_javascript = array();
    private $_css = array();
    private $_inline_scripting = array("infile" => "", "stripped" => "", "unstripped" => "");
    private $_sections = array();
    private $_cached_css = array();
    private $_cached_js = array();

    function __construct() {

        if (!defined('SPARKPATH')) {
            define('SPARKPATH', 'sparks/');
        }

        parent::__construct();
    }

    function css() {
        $css_files = func_get_args();

        foreach ($css_files as $css_file) {
            $css_file = substr($css_file, 0, 1) == '/' ? substr($css_file, 1) : $css_file;

            $is_external = false;
            if (is_bool($css_file))
                continue;

            $is_external = preg_match("/^https?:\/\//", trim($css_file)) > 0 ? true : false;

            if (!$is_external)
                if (!file_exists($css_file))
                    show_error("Cannot locate stylesheet file: {$css_file}.");

            $css_file = $is_external == FALSE ? base_url() . $css_file : $css_file;

            if (!in_array($css_file, $this->_css))
                $this->_css[] = $css_file;
        }
        return;
    }

    function js() {
        $script_files = func_get_args();

        foreach ($script_files as $script_file) {
            $script_file = substr($script_file, 0, 1) == '/' ? substr($script_file, 1) : $script_file;

            $is_external = false;
            if (is_bool($script_file))
                continue;

            $is_external = preg_match("/^https?:\/\//", trim($script_file)) > 0 ? true : false;

            if (!$is_external)
                if (!file_exists($script_file))
                    show_error("Cannot locate javascript file: {$script_file}.");

            $script_file = $is_external == FALSE ? base_url() . $script_file : $script_file;

            if (!in_array($script_file, $this->_javascript))
                $this->_javascript[] = $script_file;
        }

        return;
    }

    function start_inline_scripting() {
        ob_start();
    }

    function end_inline_scripting($strip_tags = true, $append_to_file = true) {
        $source = ob_get_clean();

        if ($strip_tags) {
            $source = preg_replace("/<script.[^>]*>/", '', $source);
            $source = preg_replace("/<\/script>/", '', $source);
        }

        if ($append_to_file) {

            $this->_inline_scripting['infile'] .= $source;
        } else {

            if ($strip_tags) {
                $this->_inline_scripting['stripped'] .= $source;
            } else {
                $this->_inline_scripting['unstripped'] .= $source;
            }
        }
    }

    function get_css_files() {
        return $this->_css;
    }

    function get_cached_css_files() {
        return $this->_cached_css;
    }

    function get_js_files() {
        return $this->_javascript;
    }

    function get_cached_js_files() {
        return $this->_cached_js;
    }

    function get_inline_scripting() {
        return $this->_inline_scripting;
    }

    /**
     * Loads the requested view in the given area
     * <em>Useful if you want to fill a side area with data</em>
     * <em><b>Note: </b> Areas are defined by the template, those might differs in each template.</em>
     *
     * @param string $area
     * @param string $view
     * @param array $data
     * @return string
     */
    function section($area, $view, $data = array()) {
        if (!array_key_exists($area, $this->_sections))
            $this->_sections[$area] = array();

        $content = $this->view($view, $data, true);

        $checksum = md5($view . serialize($data));

        $this->_sections[$area][$checksum] = $content;

        return $checksum;
    }

    function get_section($section_name) {
        $section_string = '';
        if (isset($this->_sections[$section_name]))
            foreach ($this->_sections[$section_name] as $section)
                $section_string .= $section;

        return $section_string;
    }

    /**
     * Gets the declared sections
     *
     * @return object
     */
    function get_sections() {
        return (object) $this->_sections;
    }

    /*
     * Can load a view file from an absolute path and
     * relative to the CodeIgniter index.php file
     * Handy if you have views outside the usual CI views dir
     */

    function viewfile($viewfile, $vars = array(), $return = FALSE) {
        return $this->_ci_load(
                        array('_ci_path' => $viewfile,
                            '_ci_vars' => $this->_ci_object_to_array($vars),
                            '_ci_return' => $return)
        );
    }

    /**
     * Specific Autoloader (99% ripped from the parent)
     *
     * The config/autoload.php file contains an array that permits sub-systems,
     * libraries, and helpers to be loaded automatically.
     *
     * @access	protected
     * @param	array
     * @return	void
     */
    function _ci_autoloader($basepath = NULL) {
        if ($basepath !== NULL) {
            $autoload_path = $basepath . 'config/autoload' . EXT;
        } else {
            $autoload_path = APPPATH . 'config/autoload' . EXT;
        }

        if (!file_exists($autoload_path)) {
            return FALSE;
        }

        include_once($autoload_path);

        if (!isset($autoload)) {
            return FALSE;
        }

        // Autoload packages
        if (isset($autoload['packages'])) {
            foreach ($autoload['packages'] as $package_path) {
                $this->add_package_path($package_path);
            }
        }

        // Autoload sparks
        if (isset($autoload['sparks'])) {
            foreach ($autoload['sparks'] as $spark) {
                $this->spark($spark);
            }
        }

        if (isset($autoload['config'])) {
            // Load any custom config file
            if (count($autoload['config']) > 0) {
                $CI = & get_instance();
                foreach ($autoload['config'] as $key => $val) {
                    $CI->config->load($val);
                }
            }
        }

        // Autoload helpers and languages
        foreach (array('helper', 'language') as $type) {
            if (isset($autoload[$type]) AND count($autoload[$type]) > 0) {
                $this->$type($autoload[$type]);
            }
        }

        // A little tweak to remain backward compatible
        // The $autoload['core'] item was deprecated
        if (!isset($autoload['libraries']) AND isset($autoload['core'])) {
            $autoload['libraries'] = $autoload['core'];
        }

        // Load libraries
        if (isset($autoload['libraries']) AND count($autoload['libraries']) > 0) {
            // Load the database driver.
            if (in_array('database', $autoload['libraries'])) {
                $this->database();
                $autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
            }

            // Load all other libraries
            foreach ($autoload['libraries'] as $item) {
                $this->library($item);
            }
        }

        // Autoload models
        if (isset($autoload['model'])) {
            $this->model($autoload['model']);
        }
    }

}

/** load the CI class for Modular Separation * */
(class_exists('CI', FALSE)) OR require dirname(__FILE__) . '/Ci.php';