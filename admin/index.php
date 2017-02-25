<?php echo '<?xml version="1.0"?>'; ?>
<html>
<head>
    <title>Admin area</title>
    <meta name="description" content="Herzogenhorn 2017 Anmeldung - Adminbereich">
    <meta name="author" content="OTG">
    <meta name="robots" content="none,noarchive,nosnippet,noimageindex"/>
    <link rel="icon" href="../favicon.ico">
</head>
<body>
<h1>Welcome to the administrative area of project hornherzogen</h1>
<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    echo "<p>Hello, unable to extract your username :-(.</p>";
} else {
    echo "<p>Hello, your username is '{$_SERVER['PHP_AUTH_USER']}'.</p>";
}
?>
<h2>Possible setup/verification actions</h2>
<ul>
    <li><a href="db/db_connect.php">ensure <strong>database</strong> can be connected to</a></li>
    <li><a href="db/dbtest.php">ensure <strong>database tables</strong> are working smoothly, performs a minor roundtrip based on some assumptions</a></li>
    <li><a href="db/db_rooms.php">show list of <strong>available rooms</strong> in the database</a></li>
    <li><a href="db/db_applicants.php">show list of <strong>applications</strong> and their current state</a></li>
    <li><a href="db/db_bookings.php">show list of <strong>room bookings</strong> in the database</a></li>
    <li><a href="i18n.php">ensure <strong>localization</strong> is working smoothly</a></li>
    <li><a href="path.php">show <strong>path to authentication setup</strong>, which needs to be changed manually after
            deployment to prod</a></li>
    <li><a href="php.php">show <strong>php</strong> version and extension settings</a></li>
    <li><a href="..">return to <strong>main application</strong></a></li>
</ul>
<h2>Possible admin actions - tbd</h2>
<ul>
    <li>perform bookings (set room number, match wanted partners)</li>
    <li>change state of application to trigger final confirmation mails</li>
</ul>
<h2>Current configuration</h2>
<p>
    <?php
    $config = new \hornherzogen\ConfigurationWrapper();

    print_r($config, true);
    echo $config;
    ?>
</p>
</body>
</html>
