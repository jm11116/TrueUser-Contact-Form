<?php require_once "scripts/php/user_checker.php"; ?>

<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="basic_styles.css"><!--Feel free to change-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
    <form action="" method="POST" id="contact_form">
        <p class="error">Please enable JavaScript in your browser to make an equiry.</p>
        <!--JS error message will be replaced by JavaScript with the real form data-->
    </form>
    <div id="verification_container"></div>
</body>
<footer>
    <!--<a href="hall_of_shame.php" target="_blank">View the Hall of Shame</a>-->
    <script src="scripts/js/settings.js"></script>
    <script src="scripts/js/identifier.js"></script>
    <script src="scripts/js/security.js"></script>
    <script src="scripts/js/verifier.js"></script>
</footer>
</html>