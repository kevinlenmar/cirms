<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Layout {
    private $CI;

    public function __construct() {
        $this->CI = & get_instance();
    }

    public function view($page_path, $page_contents = "", $layout, $flag = FALSE) {

        $this->data['content'] = $this->CI->load->view($page_path, $page_contents, TRUE);

        // Add assets add_global_styles($path, ifminified, ifroot, ifautoload)
        $this->CI
                 // Styles
                 ->add_global_styles('assets/css/bootstrap/bootstrap')
                 ->add_global_styles('assets/css/bootstrap-file-input/fileinput', TRUE)
                 ->add_global_styles('assets/css/toastr/toastr')
                 ->add_global_styles('assets/css/datepicker/datepicker')
                 ->add_local_styles('assets/css/cirms/cirms-colors')
                 ->add_local_styles('assets/css/cirms/cirms-positions')
                 ->add_local_styles('assets/css/cirms/cirms-style')
                 ->add_local_styles('assets/css/cirms/cirms-sidebar')
                 ->add_local_styles('assets/css/font-awesome/font-awesome')

                 //Scripts
                 ->add_global_scripts('assets/js/jquery/jquery', TRUE)
                 ->add_global_scripts('assets/js/bootstrap/bootstrap', TRUE)
                 ->add_global_scripts('assets/js/bootstrap-file-input/fileinput')
                 ->add_global_scripts('assets/js/jquery-form-validation/jquery.validate', TRUE)
                 ->add_global_scripts('assets/js/toastr/toastr', TRUE)
                 ->add_global_scripts('assets/js/datepicker/datepicker')
                 ->add_global_scripts('assets/js/datepicker/datepicker.en')
                 ->add_global_scripts('assets/js/timepicker/timepicker')
                 ->add_global_scripts('assets/js/jquery-date-format/dateFormat')
                 ->add_global_scripts('assets/js/jquery-date-format/jquery.dateFormat')
                 ->add_global_scripts('assets/js/cirms/helper/cirms-helper')
                 ->add_local_scripts('assets/js/cirms/helper/cirms-script')
                 ->add_local_scripts('assets/js/cirms/cirms/profile');

        // Load assets
        $this->data['scripts']['head'] = $this->CI->load_scripts(TRUE);
        $this->data['scripts']['body'] = $this->CI->load_scripts();
        $this->data['styles'] = $this->CI->load_styles();

        return $this->CI->load->view($layout, $this->data, $flag);
    }
}
