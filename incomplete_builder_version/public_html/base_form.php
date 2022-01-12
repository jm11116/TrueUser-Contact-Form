<!--This code is called from inside individual form index pages. This is the base that makes all the individual forms work-->
<?php session_start(); ?>
<?php
    require_once dirname($_SERVER["DOCUMENT_ROOT"], 1) . "/true_user/php/paths.php"; 
    $paths = new Paths(); 
?>
<?php require $paths->form_php . "/user_checker.php"; ?> <!--Has to be at top to kill page completely-->
<?php $xml = simplexml_load_file($GLOBALS["form_folder_path"] . "/settings.xml"); ?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <title>TrueUser Form</title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/assets/styles.css" rel="stylesheet">
  </head>

  <body class="bg-light">
  
  <?php echo "<script>var tracker_test = '" . $xml->tracker_test . "';</script>"; ?>

    <div class="container">
      <div class="py-5 text-center">
        <h2>TrueUser Contact Form</h2>
        <p class="lead">
            <?php 
                echo $xml->form_intro_text;
            ?>
        </p>
      </div>

    <?php require_once $paths->form_php . "/attempt_checker.php"; ?>
    <?php $max_attempts_reached = $attempt_checker->maxMailsReached(); ?>
    <?php

    //Make it so that you can just write with a shorthand the various form options and what they should return?

    //Form name JavaScript variable

    echo "<script> var form_html_path = './forms/" . $GLOBALS["form_folder_name"] . "/form.html';</script>";

    if ($max_attempts_reached === false){
        echo '
            <form id="contact_form" action="" method="POST">
                <p id="cookie_and_js_warning" class="error">
                    Please enable JavaScript and cookies in your browser to use this form.
                </p>
            </div>
            </form>
            <div class="col-md-12 order-md-2 mb-4">
                <div id="/verification_container" style="margin-top:-50px;"></div>
            </div>
            <script src="/assets/scripts/js/identifier.js"></script>
            <script src="/assets/scripts/js/verifier.js"></script>
            <script src="/assets/scripts/js/security.js"></script>';
    } else {
        echo '<p class="error">Maximum number of emails sent. Please wait 24 hours to use this form again.';
    }
    ?>

      <footer class="my-5 pt-5 text-muted text-center text-small">
        <br>
        <p id="company" class="mb-1" style="margin-top:-50px;">&copy; 2020</p>
        Your IP address is: <?php echo htmlspecialchars($_SERVER["REMOTE_ADDR"]); ?>
      </footer>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/assets/bootstrap/js/popper.min.js"></script>
    <script src="/assets/bootstrap /js/holder.min.js"></script>
    <script>
      // Example starter JavaScript for disabling form submissions if there are invalid fields
      (function() {
        'use strict';

        $("#company").html("&copy;" + new Date().getFullYear() + " <?php echo $xml->company; ?>");

        window.addEventListener('load', function() {
          // Fetch all the forms we want to apply custom Bootstrap validation styles to
          var forms = document.getElementsByClassName('needs-validation');

          // Loop over them and prevent submission
          var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
              if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
              }
              form.classList.add('was-validated');
            }, false);
          });
        }, false);
      })();
    </script>
  </body>
</html>
