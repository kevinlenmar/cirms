<?php

class MY_Controller extends CI_Controller {

    /* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */
    /**
     * The current request's view. Automatically guessed
     * from the name of the controller and action
     */
    protected $view;

    /**
     * An array of variables to be passed through tan(arg)o the
     * view, layout and any asides
     */
    protected $data = array();

    /**
     * A list of models to be autoloaded
     */
    protected $models = array();

    /**
     * A formatting string for the model autoloading feature.
     * The percent symbol (%) will be replaced with the model name.
     */
    protected $model_string = '%_model';

    /**
     * A list of helpers to be autoloaded
     */
    protected $helpers = array();

    /**
     * A list of scripts to be autoloaded
     */
    protected $global_scripts = array(
                                    'head'  =>  array(),
                                    'body'  =>  array()
                               );
    protected $local_scripts = array(
                                    'head'  =>  array(),
                                    'body'  =>  array()
                               );

    /**
     * A list of styles to be autoloaded
     */
    protected $global_styles = array();
    protected $local_styles = array();
    /**
     * Layout to be used
     */
    protected $template;


     /* --------------------------------------------------------------
     * GENERIC METHODS
     * ------------------------------------------------------------ */

    /**
     * Initialise the controller, tie into the CodeIgniter superobject
     * and try to autoload the models and helpers
     */
    public function __construct() {
        parent::__construct();

        $this->data[ 'directory' ] = $this->router->directory;
        $this->data[ 'controller' ] = $this->router->fetch_class();
        $this->data[ 'method' ] = $this->router->fetch_method();

        $this->_load_models();
        $this->_load_helpers();

    }
    /**
     * Remap the CI request, running the method
     * and loading the view
     */
    public function _remap($method, $arguments) {
        $this->scripts_dir = '';
        $this->styles_dir = '';

        if (method_exists($this, $method)) {
            call_user_func_array(array($this, $method), array_slice($this->uri->rsegments, 2));
        } else {
            show_404(strtolower(get_class($this)).'/'.$method);
        }
        
        $this->_load_view();
    }

    /**
     * Load a view into a layout based on
     * controller and method name
     */
    private function _load_view() {
        // Back out if we've explicitly set the view to FALSE
        if ($this->view === FALSE) { return; }

        // Get or automatically set the view and layout name
        $view = ($this->view !== null) ? $this->view . '.php' : $this->data[ 'directory' ] . 'pages/' . $this->data[ 'controller' ] . '/' . $this->data[ 'method' ] . '.php';
        // $layout = ($this->template !== null) ? $this->template . '.php' : 'layout/user.php';
        $layout = $this->template;


        // Display the layout with the view
        if($this->view === NULL) {
            $this->layout->view($view, $this->data, $layout);
        }
        else{
            $this->load->view($layout, $this->data);
            // $this->layout->view($view, $this->data, $layout);
        }

    }

    public function get_template() {
        return $this->template;
    }

    public function add_global_scripts($path, $minified = FALSE, $include_root = FALSE, $autoload = FALSE) {
        $key = ($autoload) ? 'head': 'body';

        if($include_root) {
            if($minified) {
                $this->global_scripts[$key][] = base_url() . $path .'.min.js';
            }
            else {
                $this->global_scripts[$key][] = base_url() . $path . '.js';
            }

        }
        else{
            if($minified) {
                $this->global_scripts[$key][] = $path .'.min.js';
            }
            else {
                $this->global_scripts[$key][] = $path . '.js';
            }
        }

        return $this;
    }

    public function add_local_scripts($path, $minified = FALSE, $include_root = FALSE, $autoload = FALSE) {
        $key = ($autoload) ? 'head': 'body';

        if($include_root) {
            if($minified) {
                $this->local_scripts[$key][] = base_url() . $path .'.min.js';
            }
            else {
                $this->local_scripts[$key][] = base_url() . $path . '.js';
            }

        }
        else {
            if($minified) {
                $this->local_scripts[$key][] = $path .'.min.js';
            }
            else {
                $this->local_scripts[$key][] = $path . '.js';
            }
        }

        return $this;
    }

    private function _load_scripts($autoload) {
        $javascripts = '';
        $key = ($autoload) ? 'head' : 'body';

        $scripts = array_merge($this->global_scripts[$key], $this->local_scripts[$key]);

        foreach ($scripts as $script) {
            $javascripts .= '<script src="'.$script.'"></script>'."\n\t";
        }

        return $javascripts;
    }

    public function load_scripts($autoload = FALSE) {
        return $this->_load_scripts($autoload);
    }

    public function add_global_styles($path, $minified = FALSE, $include_root = FALSE) {
        if($include_root) {
            if($minified) {
                $this->global_styles[] = base_url() . $path .'.min.css';
            }
            else {
                $this->global_styles[] = base_url() . $path . '.css';
            }

        }
        else {
            if($minified) {
                $this->global_styles[] = $path .'.min.css';
            }
            else {
                $this->global_styles[] = $path . '.css';
            }
        }

        return $this;
    }

    public function add_local_styles($path, $minified = FALSE, $include_root = FALSE) {
        if($include_root) {
            if($minified) {
                $this->local_styles[] = base_url() . $path .'.min.css';
            }
            else {
                $this->local_styles[] = base_url() . $path . '.css';
            }

        }
        else{
            if($minified) {
                $this->local_styles[] = $path .'.min.css';
            }
            else {
                $this->local_styles[] = $path . '.css';
            }
        }

        return $this;
    }

    public function session_data($key) {
        return $this->session->userdata($key);
    }

    // Session Datas
    public function get_session_data() {
        $this->data['sess_id'] = $this->session->userdata('id');
        $this->data['sess_emp_id'] = $this->session->userdata('emp_id');
        $this->data['sess_firstname'] = $this->session->userdata('firstname');
        $this->data['sess_lastname'] = $this->session->userdata('lastname');
        $this->data['sess_user_type'] = $this->session->userdata('user_type');
        $this->data['sess_access_rights'] = $this->session->userdata('access_rights');
        $this->data['sess_contact_no'] = $this->session->userdata('contact_no');
        $this->data['sess_avatar'] = $this->session->userdata('avatar');
        $this->data['sess_is_logged_in'] = $this->session->userdata('is_logged_in');
        $this->data['sess_sidebar_status'] = $this->session->userdata('sidebar_status');
        $this->data['sess_pass_alert'] = $this->session->userdata('pass_alert');
        $this->data['sess_user'] = $this->session->userdata('firstname') . ' ' .
                                             $this->session->userdata('lastname');
    }

    private function _load_styles() {
        $stylesheets = '';
        $styles = array_merge($this->global_styles, $this->local_styles);

        foreach ($styles as $style) {
            $stylesheets .= '<link rel="stylesheet" type="text/css" href="'.$style.'" />'."\n\t";
        }

        return $stylesheets;
    }


    public function load_styles() {
        return $this->_load_styles();
    }

    public function url_directory() {
        $route =& load_class('Router');
        $dir = substr_replace($route->directory, '', -1);  //Remove trailing slash
        return $dir;
    }

    public function url_class() {
        $route =& load_class('Router');
        return $route->class;
    }

    public function url_method() {
        $route =& load_class('Router');
        return $route->method;
    }

    public function get_full_url() {
        $CI =& get_instance();
        $route =& load_class('Router');

        $full_route = $route->directory . $route->class.'/'.$route->method;
        return $full_route;
    }

    /* --------------------------------------------------------------
     * MODEL LOADING
     * ------------------------------------------------------------ */

    /**
     * Load models based on the $this->models array
     */
    private function _load_models() {
        foreach ($this->models as $model) {
            $this->load->model($this->_model_name($model), $model);
        }
    }

    /**
     * Returns the loadable model name based on
     * the model formatting string
     */
    protected function _model_name($model) {
        return str_replace('%', $model, $this->model_string);
    }

    /* --------------------------------------------------------------
     * HELPER LOADING
     * ------------------------------------------------------------ */

    /**
     * Load helpers based on the $this->helpers array
     */
    private function _load_helpers() {
        foreach ($this->helpers as $helper) {
            $this->load->helper($helper);
        }
    }

    public function dump($data) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die();
    }
}

class Ajax_Controller extends CI_Controller {
    /* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */

    /**
     * A list of models to be autoloaded
     */
    protected $models = array();

    /**
     * A formatting string for the model autoloading feature.
     * The percent symbol (%) will be replaced with the model name.
     */
    protected $model_string = '%_model';

    /**
     * A list of helpers to be autoloaded
     */
    protected $helpers = array();

    /**
     * A list of scripts to be autoloaded
     */
    protected $global_scripts = array(
                                    'head'  =>  array(),
                                    'body'  =>  array()
                               );
    protected $local_scripts = array(
                                    'head'  =>  array(),
                                    'body'  =>  array()
                               );

     /* --------------------------------------------------------------
     * GENERIC METHODS
     * ------------------------------------------------------------ */

    /**
     * Initialise the controller, tie into the CodeIgniter superobject
     * and try to autoload the models and helpers
     */
    public function __construct() {
        parent::__construct();

        $this->_load_models();
        $this->_load_helpers();
    }

    /**
     * Parameters:
     *
     */
    public function mail_helper($data) {
        extract($data);

        $this->load->library('email');

        $config = array(
                'useragent'     =>  'CodeIgniter',
                'protocol'      =>  'smtp',
                'smtp_host'     =>  $smtp_host, //e.g. ssl://smtp.gmail.com
                'smtp_port'     =>  $smtp_port, //e.g. 465 or 25 for localhost
                'smtp_user'     =>  $smtp_user, //sender email
                'smtp_pass'     =>  $smtp_pass, //sender email password
                'charset'       =>  'utf-8',
                'newline'       =>  "\r\n",
                'crlf'          =>  "\r\n",
                'mailtype'      =>  $mailtype //e.g. text or html
           );

        $this->email->initialize($config);


        $this->email->from($smtp_user, $mail_title);
        $this->email->to($receiver);


        $this->email->subject($subject);

        if($mailtype === 'html') {
            $html = '<html><body>';
            $html .= $msg;
            $html .= '</body></html>';
        }

        $this->email->message($html);

        if(!$this->email->send()) {
            echo $this->email->print_debugger(array('headers', 'subject', 'body'));
        }
    }

    public function session_data($key) {
        return $this->session->userdata($key);
    }


    /* --------------------------------------------------------------
     * MODEL LOADING
     * ------------------------------------------------------------ */

    /**
     * Load models based on the $this->models array
     */
    private function _load_models() {
        foreach ($this->models as $model) {
            $this->load->model($this->_model_name($model), $model);
        }
    }

    /**
     * Returns the loadable model name based on
     * the model formatting string
     */
    protected function _model_name($model) {
        return str_replace('%', $model, $this->model_string);
    }

    /* --------------------------------------------------------------
     * HELPER LOADING
     * ------------------------------------------------------------ */

    /**
     * Load helpers based on the $this->helpers array
     */
    private function _load_helpers() {
        foreach ($this->helpers as $helper) {
            $this->load->helper($helper);
        }
    }

    public function dump($data) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die();
    }

}