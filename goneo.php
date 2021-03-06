<!DOCTYPE HTML>
<html>
<head>
    <style>
        .error {color: #FF0000;}
    </style>
</head>
<body>

<h1>Please submit the form to see the error with PHP7 at Goneo</h1>

<?php
// catch first load
if (!isset($count)) {
    $count = 10;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['count']) || !is_numeric($_POST['count'])) {
        $count = 10; // fallback to default
    } else {
        $count = test_input($_POST['count']);
    }
}

// https://github.com/ottlinger/hornherzogen/issues/19
function test_input($data)
{
    $data = trim(''.$data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    // this seems to break things with PHP at Goneo
    $preferences = ['input-charset' => 'UTF-8', 'output-charset' => 'UTF-8'];
    $encoded = iconv_mime_encode('Subject', $data, $preferences);
    echo '<h3>IconvMimeEncodedToUTF8: '.$encoded = substr($encoded, strlen('Subject')).'</h3>';

    return $data;
}
?>

<h2>PHP <?php echo phpversion(); ?> - Form Validation - let's see if parameters are missing during conversion into $_POST array</h2>

<h2>Form example</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <label for"count">Anzahl:</label><input type="text" name="count" value="<?php if (isset($count)) {
    echo $count;
}?>">
    <br><br>
    <input type="submit" name="submit" value="Submit" autofocus>

    <?php
    echo '<h2>Your Input:</h2>';
    echo '<pre>';
    echo '<p>RAW data after submit:</p>';
    var_dump(file_get_contents('php://input'));
    echo '<p>Converted to POST:</p>';
    var_dump($_POST);
    echo '</pre>';
    ?>

    <h3>Autogenerated field values to be ignored ;-)</h3>
    <?php

    for ($i = 1; $i <= $count; $i++) {
        echo '
<!-- start '.$i.' -->
        <br><br>
        Name '.$i.': <input type="text" name="name'.$i.'" value="Do not edit field '.$i.'">
        <br><br>
         <div class="radio" id="flexible'.$i.'">
                        <label>
                            <input type="radio" name="flexible'.$i.'" id="no" value="no'.$i.'" checked>
                            NeinNein
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="flexible'.$i.'" id="yes" value="yes'.$i.'">
                            Ja Ja
                        </label>
                    </div>
<!-- end '.$i.' -->
    ';
    }

    ?>

</form>

</body>
</html>