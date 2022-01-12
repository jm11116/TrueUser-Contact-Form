<?php session_start(); $_SESSION["script_access"] = "xTfrzRTJhpLt13#@!CvccczzzssaLkiPPO0998"; ?>
<?php require "form_scanner.php"; $form_scanner = new FormScanner(); ?>
<?php require "xml_loader.php"; $xml_loader = new XMLLoader(); $xml = $xml_loader->loadXML(); ?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" href="../favicon.ico">
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="../jquery_ui/jquery-ui.min.js"></script>
<link href="../jquery_ui/jquery-ui.css" rel="stylesheet">

<title>TrueUser Admin</title>

<!-- Bootstrap core CSS -->
<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="../styles.css" rel="stylesheet">
</head>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
<a class="navbar-brand" href="#">TrueUser Form Builder Admin</a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbar">
<ul class="navbar-nav ml-auto">
<div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Your Forms
  </button>
  <div class="dropdown-menu" id="your_forms" aria-labelledby="dropdownMenuButton">
    <?php $form_scanner->echoFormNamesAsNav(); ?>
  </div>
</div>
    <li class="nav-item">
    <a class="nav-link" href="#form_builder">Form Builder</a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href="#form_options">Form Options</a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href="#blocking">Blocking</a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href="#form_limits">Form Limits</a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href="#security">Security<span class="sr-only"></span></a>
    </li>
    </li>
    <li class="">
    <a class="nav-link" id="view_current_form_button" href=""><button class="navbar-btn">View Form</button><span class="sr-only"></span></a>
    </li>
    <li class="">
    <a class="nav-link" href=""><button class="navbar-btn">Log Out</button><span class="sr-only"></span></a>
    </li>
</ul>
</div>
</nav>

<div class="col-md-12 order-md-2 mb-4">
<br><br>

<div class="container">

<br><br>

<div class="container">
<div class="row">

<div class="col-md-6 mb-3" id="form_builder" data-spy="scroll">
<h3 id="current_form_name">Editing: <i><?php $form_scanner->echoFirstFormName(); ?></i></h3>

<ul id="sortable">
<!--Form data will go here to become draggable-->
</ul>
</div>

<body class="bg-light" data-spy="scroll" data-target=".navbar" data-offset="100">

<?php echo $form_scanner->getFormURLPartJavaScript(); ?>
<br>
<?php echo $form_scanner->echoFirstFormAsJSVar(); ?>

<div class="col-md-6 mb-3">
<h3 style="margin-bottom: 8px;">Create New Form Field</h3>
    <form action="" method="POST" id="create_form">
        <label for="input_type">Input type:</label>
        <select id="input_type" name="type" class="custom-select mb-3">
            <option value="input">Text</option>
            <!--<option value="email">Email</option>--><!--Feature removed to not interfere with verification-->
            <option value="textarea">Textarea</option>
            <option value="heading">Heading</option>
            <option value="divider">Divider</option>
            <option value="spacer">Spacer</option>
        </select><br>
        <label for="label">Label: </label>
        <input type="text" id="label" name="label" class="form-control">
        <label for="form_name">Form name: </label>
        <input type="text" id="form_name" name="form_name" class="form-control lowercase" disabled hidden>
        <label for="height">Height (number of pixels): </label>
        <input type="text" id="height" name="height" value="20" class="form-control">
        <label for="radio">Required: </label>
        <input type="radio" id="required_true" name="required" checked><label for="radio" class="radio-inline">True</label>
        <input type="radio" id="required_false" name="required"><label for="radio" class="radio-inline">False</label>
    </form>

    <div class="btn-group mt-3">
        <button type="button" id="create_button" class="btn btn-primary">Add Field</button>
        <button class="btn btn-primary" id="create_new_form">Create New Form</button>
        <button class="btn btn-primary" id="delete_this_form">Delete This Form</button>
    </div>
    
    </div>
</div>
</div>

<div class="col-md-12 mb-3"><hr style="margin-top:50px;margin-bottom:50px;"></div>

<div class="col-md-12 mb-3" id="form_options">
<h3>Form Sharing Options</h3>
</div>

<div class="col-md-12 mb-3">
<label for="to_email" class="form-label">Form URL: </label>
<input type="text" name="share_url" id="share_url" class="form-control" value="" readonly>
</div>

<div class="col-md-12 mb-3"><hr style="margin-top:50px;margin-bottom:50px;"></div>

<form id="settings_form" action="" method="">

<div class="col-md-12 mb-3" id="form_options">
<h3>Form Options</h3>
</div>

<div class="col-md-12 mb-3">
<label for="to_email" class="form-label">Form destination address: </label>
<input type="text" name="to_email" class="form-control" value="<?php echo $xml->to_email; ?>">
</div>

<div class="col-md-12 mb-3">
<label for="domain" class="form-label">Domain name (format like this website.com)</label>
<input type="text" name="domain" class="form-control" value="<?php echo $xml->domain; ?>">
</div>

<div class="col-md-12 mb-3">
<label for="form_title" class="form-label">Title: </label>
<input type="text" name="form_title" class="form-control" value="<?php echo $xml->form_title; ?>">
</div>

<div class="col-md-12 mb-3">
<label for="form_intro_text" class="form-label">Form intro text (displayed under title): </label>
<input type="text" name="form_intro_text" class="form-control" value="<?php echo $xml->form_intro_text; ?>">
</div>

<div class="col-md-12 mb-3">
<label for="company" class="form-label">Company name (displayed at bottom of form): </label>
<input type="text" name="company" class="form-control" value="<?php echo $xml->company; ?>">
</div>

<div class="col-md-12 mb-3">
<input class="btn btn-primary btn-lg btn-block application_submit" value="Save"></input>
</div>

<div class="col-md-12 mb-3"><hr style="margin-top:50px;margin-bottom:50px;"></div>

<div class="col-md-12 mb-3" id="blocking">
<h3>Blocking</h3>
</div>

<div class="col-md-12 mb-3" id="non_western_radio_div">
<label for="non_western_country_ban" class="form-label">Block non-Western countries from using form: </label>
<input type="radio" id="non_western_ban_on" name="non_western_country_ban" value="on" <?php echo $xml_loader->non_western_block_true_val; ?>><label for="on">On</label>
<input type="radio" id="non_western_ban_off" name="non_western_country_ban" value="off" <?php echo $xml_loader->non_western_block_false_val; ?>><label for="on">Off</label>
</div>

<div class="col-md-12 mb-3" id="tracker_test_radio_div">
<label for="tracker_test" class="form-label">Block users with tracking scripts disabled: </label>
<input type="radio" id="tracker_test_on" name="tracker_test" value="on" <?php echo $xml_loader->tracker_test_true_val; ?>><label for="on">On</label>
<input type="radio" id="tracker_test_off" name="tracker_test" value="off" <?php echo $xml_loader->tracker_test_false_val; ?>><label for="on">Off</label>
</div>

<div class="col-md-12 mb-3">
<label for="bad_ips" class="form-label">IP addresses to block (separated by commas):</label>
<input type="text" name="bad_ips" class="form-control" value="<?php echo $xml->bad_ips; ?>">
</div>

<div class="col-md-12 mb-3">
<label for="ips_to_crash" class="form-label">Crash browser tab from users visiting with these IPs: </label>
<input type="text" name="ips_to_crash" class="form-control" value="<?php echo $xml->ips_to_crash; ?>">
</div>

<div class="col-md-12 mb-3">
<input type="button" class="btn btn-primary btn-lg btn-block application_submit" value="Save"></input>
</div>

<div class="col-md-12 mb-3"><hr style="margin-top:50px;margin-bottom:50px;"></div>

<div class="col-md-12 mb-3" id="form_limits">
<h3>Form Limits</h3>
</div>

<div class="col-md-12 mb-3">
<label for="max_mails_per_day" class="form-label">Max emails per day: </label>
<input type="text" name="max_mails_per_day" class="form-control" value="<?php echo $xml->max_mails_per_day; ?>">
</div>

<div class="col-md-12 mb-3">
<label for="max_codes_per_day" class="form-label">Max verification codes per email address per day: </label>
<input type="text" name="max_codes_per_day" class="form-control" value="<?php echo $xml->max_codes_per_day; ?>">
</div>

<div class="col-md-12 mb-3">
<label for="attempts_until_timeout" class="form-label">Numbers of verification codes before delay: </label>
<input type="text" name="attempts_until_timeout" class="form-control" value="<?php echo $xml->attempts_until_timeout; ?>">
</div>

<div class="col-md-12 mb-3">
<label for="code_timeout_secs" class="form-label">Base verification code delay in seconds: </label>
<input type="text" name="code_timeout_secs" class="form-control" value="<?php echo $xml->code_timeout_secs; ?>">
</div>

<div class="col-md-12 mb-3">
<label for="code_expiry_mins" class="form-label">Minutes until verification codes expire: </label>
<input type="text" name="code_expiry_mins" class="form-control" value="<?php echo $xml->code_expiry_mins; ?>">
</div>

<div class="col-md-12 mb-3">
<label for="form_reset_time_hours" class="form-label">Hours until form is reset for a user: </label>
<input type="text" name="form_reset_time_hours" class="form-control" value="<?php echo $xml->form_reset_time_hours; ?>">
</div>

<div class="col-md-12 mb-3">
<input class="btn btn-primary btn-lg btn-block application_submit" value="Save"></input>
</div>

<div class="col-md-12 mb-3"><hr style="margin-top:50px;margin-bottom:50px;"></div>

<div class="container">
<div class="row">

<div class="col-md-12 mb-3" id="security">
<h3 style="margin-bottom: 8px;">Security</h3>
    <form action="" method="POST" id="create_form">

    </form>

    <div class="btn-group mt-3">
        <button type="button" id="create_button" class="btn btn-primary">Change Password</button>
    </div>
    
    </div>
</div>
</div>

<div class="col-md-12 mb-3"><hr style="margin-top:50px;margin-bottom:50px;"></div>


<div class="col-md-12 mb-3">
<h3>Access History</h3>
</div>

<div class="col-md-12 mb-3">
<textarea id="access_history" readonly></textarea>
</div>

<div class="col-md-12 mb-3"><hr style="margin-top:50px;margin-bottom:12px;"></div>

<div class="col-md-12 mb-3">
<input class="btn btn-primary btn-lg btn-block application_submit" value="Save"></input>
</div>

</form>

<footer class="my-5 pt-5 text-muted text-center text-small">
<p id="company" class="mb-1" style="margin-top:-50px;">&copy; 2020</p>
Your IP address is: <?php echo htmlspecialchars($_SERVER["REMOTE_ADDR"]); ?>
</footer>
</div>

</div>
</div>

<script src="application.js"></script>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="../bootstrap/assets/js/vendor/popper.min.js"></script>
<script src="../bootstrap/assets/js/vendor/holder.min.js"></script>
<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
'use strict';

$("#company").html("&copy;" + new Date().getFullYear() + " <?php echo $settings->company; ?>");

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