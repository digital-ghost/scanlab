<?php
##
#  R007 p4n31
##

class Root extends Scanlab {
    function __construct() {
        parent::__construct();
        if (!$this->user) redirect(REL_URL."auth/login");
        if ($this->user !== ROOT_USER) showError("You shall not pass");
    }

    function GET($matches) {
        if (isset($matches[2]) && !empty($matches[2])) {
            switch($matches[2]) {
                case "users":
                    $total = $this->db->reports->find()->count(); // total number of entries
                    $users = $this->db->users->find();
                    //extend users with sum info
                    foreach ($users as $user){
                        $user["favorites_count"] = count($user['favorites']);
                        $users_extended[] = $user; 
                    }
                    $stats = array(); // statistics
                    $vars = array( 'total' => $total, 'users' => $users_extended );
                    $this->view('root/users.html', $vars);
                    break;
                case "stats":
                    $vars = array(
                        "disk" => array("total" => ceil(disk_total_space("/") /1048576 ), "free" => ceil(disk_free_space("/") /1048576 )),
                        "db" => array(
                            "users" => $this->db->command(array("collStats" => "users", "scale" =>1048576 )),
                            "reports" => $this->db->command(array("collStats" => "reports", "scale" =>1048576 )),
                        )
                    );
                    $this->view('root/stats.html', $vars);
                    break;
                default:
                    redirect(REL_URL."root/stats");
            }
            
        } else {
            redirect(REL_URL."root/stats");
        }
    }

    function POST(){
        if (isset($_POST['action']) && !empty($_POST['action'])) {
            $this->checkToken();
            switch ($_POST['action']) {

                case "enable_api":
                    echo $this->apiChange(1);
                    break;
                
                case "disable_api":
                    echo $this->apiChange(0);
                    break;

                case "update_limit":
                    echo $this->updateLimit();
                    break;

                case "add_user":
                    echo $this->addUser();
                    break;

                case "del_user":
                    $this->delUser();
                    break;

                case "del_reports":
                    $this->delReports();
                    break;

                case "count_reports":
                    $this->countReports();
                    break;

                case "update_cache":
                    $this->updateCache();
                    break;

                default:
                    die("hurr");
            }
        }
    }

    private function apiChange($value){
        if (isset($_POST['username']) && !empty($_POST['username'])){
            if (preg_match('/[^a-z0-9]+/', $_POST['username'])) return 0;
            try {
                $this->db->users->update(
                    array("username" => $_POST['username']), array('$set' => array("api_key" => $value))
                );
                return 1;
            } catch(Exception $e) {
                return 0;
            }
        
        } else {
            return 0;
        }
    }

    private function updateLimit() {
        if (isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['limit']) && !empty($_POST['limit'])) {
            if (preg_match('/[^a-z0-9]+/', $_POST['username'])) redirect(REL_URL.'root/users');
            try {
                $this->db->users->update(
                    array("username" => $_POST['username']), array('$set'=>array("account_limit"=>(int)$_POST['limit']))
                );
            } catch (Exception $e) {
                showError("Something went wrong");
            }
            redirect(REL_URL.'root/users');
        } else {
            redirect(REL_URL.'root/users');
        }
    
    }

    public function delUser() {
        if (isset($_POST['username']) && !empty($_POST['username'])) {
            $username = (string) $_POST['username'];
            if (preg_match('/[^a-z0-9]+/', $username)) die("0");
            try {
                $this->db->users->remove(array('username' => $username));
                $this->db->reports->remove(array('user' => $username));
                $this->db->comments->remove(array('user' => $username));
                die("1");
            } catch (Exception $e) {
                die("0");
            }
        } else {
            die("0");
        }
    }

    public function delReports() {
        if (isset($_POST['username']) && !empty($_POST['username'])) {
            $username = (string) $_POST['username'];
            if (preg_match('/[^a-z0-9]+/', $username)) die("0");
            try {
                $this->db->reports->remove(array('user' => $username));
                $this->db->users->update(
                    array("username" => $_POST['username']), array('$set' => array("reports_count" => "0"))
                );
                die("1");
            } catch (Exception $e) {
                die("0");
            }
        } else {
            die("0");
        }
    }

    public function countReports() {
        if (isset($_POST['username']) && !empty($_POST['username'])) {
            $username = (string) $_POST['username'];
            if (preg_match('/[^a-z0-9]+/', $username)) die("0");
            try {
                updateUserReportCount($username, $this->db);
                die("1");
            } catch (Exception $e) {
                die("0");
            }
        } else {
            die("0");
        }
    }

    private function addUser() {
        if (isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])) {
            $user = (string) $_POST['username'];
            $pass = (string) $_POST['password'];
            //check unique username
            if ($this->db->users->find( array("username" => $user) )->count() > 0) showError("username already taken");
            $this->registerUser($user, $pass);
            redirect(REL_URL."root/users");
        } 
    }

    private function updateCache() {
        updateOverviewCache($this->db);   
        redirect(REL_URL."root");
    }

}
