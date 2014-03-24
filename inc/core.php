<?php
##
#  That is just script, that includes all files
##

$require = array(
    "settings.php",
    "libraries/glue.php",
    "libraries/captcha/captcha.php",
    "libraries/Twig/Autoloader.php",
    "base_functions.php",
    "search_functions.php",
    "html.php",
    "scanlab.php",
    "urls.php",
    "db.php"
);

foreach ($require as $file) {
    require_once($file);
}

if (SITE_OFFLINE === true) showError('Site is offline');

$classes = array_diff(scandir('inc/classes'), array('.', '..'));
foreach ($classes as $class) {
    require_once('inc/classes/'.$class);
}

$g = new glue();
try {
    $g::stick($urls);
} catch (Exception $e) {
    showError("Ouch! You must be doing it wrong!");
}

?>
