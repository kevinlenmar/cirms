<!DOCTYPE html>
<html lang="en">
    <head>
    	<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <base href="<?php echo base_url() ?>" >
        <link rel="shortcut icon" href="assets/images/logo/cirms.png" type="image/x-icon">
        <link rel="icon" href="assets/images/logo/cirms.png" type="image/x-icon">
        <link href="assets/css/bootstrap/bootstrap.css" rel="stylesheet">
        <link href="assets/css/cirms/cirms-colors.css" rel="stylesheet">
        <title><?php $title ?></title>
        <style type="text/css">
            .container {
                display: table;
                height: 100%;
                position: absolute;
                overflow: hidden;
                width: 100%;
            }
            .helper {
                /*#position: absolute; /*a variation of an "lte ie7" hack*/
                /*#top: 50%;*/
                display: table-cell;
                vertical-align: middle;
            }
            .content {
                /*#position: relative;
                #top: -50%;*/
                margin:0 auto;
            }
        </style>
    </head>
    <body class="bg-maroon fg-gold">
        <div class="container text-center">
            <div class="helper">
                <div class="content">
                    <img src="assets/images/logo/cirms-error-2.png">
                    <h1><?php echo $error_header ?></h1>
                    <h3><?php echo $error_content?></h3>
                    <p><?php echo $error_footer ?></p>
                     <a href="<?php base_url() ?>">
                        <button class="btn btn-primary">Return Home</button>
                    </a>
                </div>
            </div>
        </div>
	</body>
</html>