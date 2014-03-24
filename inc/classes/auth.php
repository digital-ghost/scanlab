<?php
##
#   Auth: register, login, logout
##

class Auth extends Scanlab {
    // Views and shit
    function GET($matches) {
        if (isset($matches[2]) && !empty($matches[2])) {
            switch ($matches[2]) { 
                case "register":
                    if ($this->checkLogin()) redirect("/");
                    if (FREE_REGISTRATION === false) $this->renderError("Registration is disabled");
                    $this->view("auth/register.html");
                    break;
                case "login":
                    if ($this->checkLogin()) redirect("/");
                    $this->view("auth/login.html");
                    break;
                case "logout":
                    $this->logout();
                    redirect(REL_URL);
                    break;
                default:
                    redirect(REL_URL);
            }
        } else {
            redirect(REL_URL);
        }
    }

    //POST requests handler
    function POST($matches) {
        if (isset($matches[2]) && !empty($matches[2])) {
            switch ($matches[2]) { 
                case "register":
                    ##
                    #   Register handler
                    ##
                    if (FREE_REGISTRATION === false) showError("Sorry, registation is closed");
                    if (
                            !isset($_SESSION["captcha"]) || !isset($_POST["captcha"]) 
                                || $_POST["captcha"] !== $_SESSION["captcha"]
                    ) {
                        showError("Wrong captcha");
                    }
                    if (
                            isset($_POST['username']) && !empty($_POST['username']) 
                                && isset($_POST['password']) && !empty($_POST['password'])
                    ) {
                        $user = (string) $_POST['username'];
                        $pass = (string) $_POST['password'];
                        $this->checkRegister($user, $pass);
                        $this->registerUser($user, $pass);
                        redirect(REL_URL."auth/login");
                    } else {
                        showError("missing username or password");
                    }
                    break;
                case "login":
                    ##
                    #   Login handler
                    ##
                    if (
                        isset($_POST['username']) && !empty($_POST['username']) 
                        && isset($_POST['password']) && !empty($_POST['password'])
                    ) {
                        $user = (string) $_POST['username'];
                        $pass = sha1( 
                            sha1((string)$_POST['password']) . AUTH_SALT
                        );
                        $curr_user = $this->checkAuth($user, $pass, true);
                        if ($curr_user) {
                            $this->login($curr_user);
                            redirect(REL_URL."user/panel");
                        } else {
                            showError("Wrong username/password");
                        }
                    } else {
                        showError("missing username or password");
                    }
                    break;
                default:
                    redirect(REL_URL."user/login");
            }
        } else {
            redirect(REL_URL."user/login");
        }
    }




    ##
    #   Methods
    ##

    // USERNAME and PASSWORD validation
    private function checkRegister($user, $pass) {
        //check registration limit
        if ($this->db->users->find()->count() >= USER_REGISTRATION_LIMIT)
            showError("registration limit reached");
        //username check
        if (preg_match("/[^a-z0-9]+/", $user) || strlen($user) < 4 || strlen($user) > 16) 
            showError("username must be 4-16 alnum string");
        //pass check
        if (preg_match("/[^a-z0-9A-Z!@#$%&]+/", $pass) || strlen($pass) < 8 || strlen($pass) > 32) 
            showError("password must be 8-32 symbols. special chars allowed: !@#$%&");
        if ($user === $pass) showError("username and password must not be the same, you dumb fuck!");
        //check unique username
        if ($this->db->users->find( array("username" => $user) )->count() > 0)
            showError("username already taken");
        return true;
    }

    // login session 
    private function login($user) {
        $_SESSION["username"] = $user["username"];
        $_SESSION["logged_in"] = "true";
        if ($user["api_key"] == "1") $_SESSION["api_key"] = "true";
        setcookie("sl_login", "true", 0, "/");
    }

    // logout session 
    private function logout() {
        setcookie("sl_login", "", time() - 3600, "/");
        setcookie("sl_favs", "", time() - 3600, "/");
        $_SESSION["username"] = "";
        $_SESSION["logged_in"] = "";
        session_destroy();
    }

}

?>
