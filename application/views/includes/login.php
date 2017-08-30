<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <base href="<?php echo base_url() ?>" >
        <link rel="shortcut icon" href="assets/images/logo/cirms.png" type="image/x-icon">
        <link rel="icon" href="assets/images/logo/rms.png" type="image/x-icon">
        <?php
            echo '<title>CIRMS</title>';
            echo $styles . $scripts['head'];
        ?>
    </head>

    <body>

        <?php echo $content; ?>

        <?php echo $scripts['body']; ?>
    </body>

</html>