<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Enable to format in mobile version -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <base href="<?php echo base_url() ?>" >
        <link rel="shortcut icon" href="assets/images/logo/cirms.png" type="image/x-icon">
        <link rel="icon" href="assets/images/logo/cirms.png" type="image/x-icon">
        <?php
            if($controller != 'report') {
                echo '<title>CIRMS | ' . ($controller === 'cirms' ? preg_replace('/[^a-zA-Z0-9]/', ' ', ucwords($method)) : preg_replace('/[^a-zA-Z0-9]/', ' ', ucwords($controller))) . '</title>';
            }
            # Print Styles from libraries/Layout.php #
            echo $styles . $scripts['head'];
        ?>
    </head>
    <body data-controller="<?php echo $controller ?>" data-method="<?php echo $method ?>">
        <input type="hidden" id="base_url" value="<?php echo base_url() ?>" />
        <div id="wrapper">
            <?php $this->load->view('includes/navbar'); ?>
            <div id="sub-wrapper" class="<?php echo $sess_sidebar_status ?>">
                <?php $this->load->view('includes/sidebar'); ?>
                <div id="page-content-wrapper">
                    <div class="page-content inset">
                        <?php $this->load->view('includes/ribbon'); ?>
                        <div class="row hide-this">
                            <div class="col-lg-12">
                                <!-- Check if user has already changed the default password -->
                                <?php if( !($sess_pass_alert) && !($this->user->check_if_pass_changed($sess_id)) ) : ?>
                                    <div class="alert alert-dismissible bg-info fg-dark" role="alert" style="border: 1px solid #66a7e8">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <i class="fa fa-info-circle fg-darkCyan"></i>
                                        <small class="metro-font"><strong class="fg-darkCyan">Important: </strong>To add more security to your account, please change your default password <a href="settings" class="fg-darkCyan">here</a>.</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                      </body>
</html>
