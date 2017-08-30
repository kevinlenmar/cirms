<?php

class MY_Model extends CI_Model {

    /**
     * A list of models to be autoloaded
     */
    protected $_models = array();
    /**
     * A formatting string for the model autoloading feature.
     * The percent symbol (%) will be replaced with the model name.
     */
    protected $model_string = '%_Model';

    /**
     * A list of user types
     */ protected $_types = array();

    /**
     * A long string used for hashing
     */
    protected $_salt;

    /**
     * Prefix name for all tables
     */
    protected $_table_prefix = 'cirms_';


    protected $_approved = 1;
    protected $_pending = 0;
    protected $_null = 0;
    protected $_avatar_colors = array(
                                    'brown',
                                    'black',
                                    'pink',
                                    'red',
                                    'blue',
                                    'purple',
                                    'deeppurple',
                                    'cyan',
                                    'lightblue',
                                    'teal',
                                    'green',
                                    'lightgreen',
                                    'lime',
                                    'yellow',
                                    'amber',
                                    'orange',
                                    'deeporange',
                                    'gray',
                                    'bluegray',
                                    'indigo'
                                );

    /**
     * Prefix for temporary tables
     */
    protected $_temp_prefix = 'temp_';


    /**
     * Initialise the controller, tie into the CodeIgniter superobject
     * and try to autoload the models and helpers
     */
    public function __construct() {
        parent::__construct();

        $this->_salt = 'mRhgu2fqEkNcOuvQL9*deffI9y*6T1~puRL8mmz7T0XX7G9rw%57Mg3aPfB^';

        $this->_load_models();
    }

    /* --------------------------------------------------------------
     * MODEL LOADING
     * ------------------------------------------------------------ */

    /**
     * Load models based on the $this->models array
     */
    private function _load_models() {
        foreach ($this->_models as $model) {
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

    public function dump($data) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die();
    }
}