<?php
##
#   Core class
##

class Scanlab {
    function __construct() {
        $this->db_cli = new MongoClient(DB_SERVER);
        $this->db = $this->db_cli->selectDB(DB_NAME);
        Twig_Autoloader::register();
        $this->tl = new Twig_Loader_Filesystem('inc/templates');
        $this->twig = new Twig_Environment($this->tl);
        //sikurity headerz
        header("X-Frame-Options: DENY");
        header("Content-Security-Policy: script-src 'self'");
        session_start();
        $this->twig->addGlobal('session', $_SESSION);
        $this->twig->addGlobal('GET', $_GET);
        $this->twig->addGlobal('REL_URL', REL_URL);
        $this->twig->addGlobal('ROOT_USER', ROOT_USER);
        $this->twig->addGlobal('ADMIN_EMAIL', ADMIN_EMAIL);
        $this->twig->addGlobal('SL_ADVERT', SL_ADVERT);
        $this->twig->addGlobal('SL_VERSION', SL_VERSION);

        $this->user = $this->checkLogin();
    }

    public function view($name, $val=array()) {
        echo $this->twig->render($name, $val);
    }

    public function renderError($text) {
         echo $this->view('error.html', array("error_message" => $text));
         exit();
    }

    // Authenification check (for login and inserting/fetching api)
    public function checkAuth($user, $hash, $return_user="false") {
        if (preg_match("/[^a-z0-9]+/", $user) || strlen($user) < 4 || strlen($user) > 16) 
            return false;
        if (preg_match("/[^a-z0-9]+/", $hash) || strlen($hash) !== 40) 
            return false;
        $query = array("username" => $user, "password" => $hash);
        $user_row = $this->db->users->findOne($query);
        if ($user_row !== NULL) { 
            if ($return_user === "false") {
                return true;
            } else {
                return $user_row;
            }
        } else {
            return false;
        }
    }

    // Login check (session checks for accessing website)
    public function checkLogin($getuser=true) {
        if ( isset($_SESSION["logged_in"]) && isset($_SESSION["username"])) {
            if ($getuser) {
                return $_SESSION["username"];
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    // Register user in the database 
    public function registerUser($user, $pass) {
        try {
            $this->db->users->insert(
                array(
                    "username" => $user,
                    "password" => sha1(sha1($pass).AUTH_SALT),
                    "favorites" => array(),
                    "achievemnts" => array(),
                    "joined_at" => time(),
                    "account_limit" => DEFAULT_ACCOUNT_LIMIT,
                    "targets" => "",
                    "reports_count" => 0,
                    "api_key" => 0
                )
            );
        } catch(Exception $e) {
            showError("something went wrong");
        } 
    }

    // Check if id exists in reports collection
    public function checkReportId($id) {
        if (preg_match('/[^0-9a-zA-Z]+/', $id)) $this->renderError('you fail');
        try {
            $result = $this->db->reports->findOne( array( "_id" => new MongoId($id) ) );
            if (!$result) showError("no entry with such id");
        } catch (Exception $e) {
            showError("wrong id");
        }
        return $result;
    }

    public function checkToken() {
        if (!isset($_POST["token"]) || $_POST["token"] !== $_SESSION["token"]) {
            showError("Invalid security token.");
        }
    }
    
}
