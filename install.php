<?php
##
# Installation script
# Delete this file after installation
##

require('inc/settings.php');
require('inc/html.php');

# Check all php extentions
if (!extension_loaded('mongo')) {
    showError('ERROR! <a href="http://www.php.net/manual/en/book.mongo.php">Install php5 mongo extention.</a>');
}
if (!extension_loaded('gd')) {
    showError('ERROR! <a href="http://www.php.net/manual/en/book.image.php">Install php5 GD extention.</a>');
}
if (!extension_loaded('geoip')) {
    showError('ERROR! <a href="http://www.php.net/manual/en/book.geoip.php">Install php5 geoip extention.</a>');
}
if (!extension_loaded('json')) {
    showError('ERROR! <a href="http://www.php.net/manual/en/book.json.php">Install php5 json extention.</a>');
}

$cli = new MongoClient(DB_SERVER);
$db = $cli->selectDB(DB_NAME);

$root_user = $db->users->findOne(array('username' => ROOT_USER));

if (isset($_POST['pass']) && !empty($_POST['pass'])) {
	if ($root_user == NULL) {
        try {
            $db->users->insert(
                array(
                    "username" => ROOT_USER,
                    "password" => sha1(sha1((string) $_POST['pass']).AUTH_SALT),
                    "favorites" => array(),
                    "achievemnts" => array(),
                    "joined_at" => time(),
                    "account_limit" => DEFAULT_ACCOUNT_LIMIT,
                    "targets" => "",
                    "reports_count" => 0,
                    "api_key" => 1
                )
            );
        } catch(Exception $e) {
            showError("something went wrong");
        }
        showError('Root user created. You can login now!');
	}
}

if ($root_user == NULL) {
	# Create new root user!
	$message = 'Enter new password for user '.ROOT_USER. ':';
	$message .= '<form action="" method="POST">';
	$message .= '<input type="text" name="pass" value="letadminin"><input type="submit" value="save"></form>';

	showError($message);
}

showError('Installation completed.');