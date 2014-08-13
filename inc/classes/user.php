<?php
class User extends Scanlab {
    function __construct() {
        parent::__construct();
        if (!$this->user) redirect(REL_URL."auth/login");
    }

    function GET($matches) {
        if (isset($matches[2])) {
            switch ($matches[2]) {
                case "panel":
                    $vars = array(
                        "user" => $this->db->users->findOne(array("username" => $this->user)),
                    );
                    $this->view("user/panel.html", $vars);
                    break;
                case "favs":
                    $page = getPage();
                    $favs = $this->getUserFavorites();
                    if (empty($favs)) $this->renderError('you dont have any favorites yet');
                    foreach ($favs as $fav) {
                        $query['$or'][] = array('_id' => new MongoId($fav));
                    }

                    // results on page
                    $limit = 10;
                    $total = $this->db->reports->find($query)->count(); // total number of entries
                    if ($total == 0) showError('you dont have any favorites yet');
                    // last page number
                    $pages_index = ceil($total / $limit);
                    if ($page > $pages_index) showError('you fail');

                    $skip = ($page * $limit) - $limit;
                    $results = $this->db->reports->find($query)->skip($skip)->limit($limit);
                    $pagination = pagination_init(array(
                        "pages" => $pages_index,
                        "current" => $page,
                        "base_url" => REL_URL."user/favs?p="
                    ));

                    $vars = array("total_results" => $total,"results" => $results,"pagination" => $pagination);

                    $this->view("results.html", $vars);
                    break;
                case "json_favs":
                    $favs = $this->getUserFavorites();
                    if (empty($favs)) $favs = array("empty");
                    header("Content-Type: application/json");
                    echo json_encode($favs);
                    break;
                default:
                    redirect(REL_URL."user/panel");
            }
        } else {
            redirect(REL_URL."user/panel");
        }
    }

    function POST($matches) {
        if (isset($matches[2])) {
            $this->checkToken();
            switch ($matches[2]) {
                case "add_fav":
                    #   Add id to favorites
                    $this->add_fav();
                    break;
                case "del_fav":
                    #   Delete id from favorites
                    $this->del_fav();
                    break;
                case "add_targets":
                    #   Add or change targets list
                    $this->add_targets();
                    break;
                default:
                    die("sasay");
            } 
        }
    }




    ##
    #  Functions
    ##

    // add to favorites
    private function add_fav(){
        if ($this->_post("id")) {
            $id = (string) $_POST["id"];
            $report = $this->checkReportId($id);
            $favorites = $this->getUserFavorites();
            if (!in_array($id, $favorites) && count($favorites) < 1000) {
                $favorites[] = $id;
                echo $this->updateFavorites($favorites);
                echo $this->rate($report, "up");
            } else {
                die(0);
            }
        }
    }

    //delete from favorites
    private function del_fav(){
        if ($this->_post("id")) {
            $id = (string) $_POST["id"];
            $report = $this->checkReportId($id);
            $favorites = $this->getUserFavorites();
            if (in_array($id, $favorites)) {
                $favorites = array_diff($favorites, [$id]);
                echo $this->updateFavorites($favorites);
                echo $this->rate($report, "down");
            } else {
                die(0);
            }
        }
    }

    // add or update targets for client
    private function add_targets(){
        if ($this->_post('targets')) {
            $targets = (string) $_POST['targets'];
            if (strlen($targets) > 9000) showError("Your target list is over nine thousand");
            try {
                $this->db->users->update(array('username'=>$this->user), array('$set'=>array("targets"=> trim($targets))));
            } catch (Exception $e) {
                showError("wtf");
            }
            redirect(REL_URL."user/panel");
        } else {
            showError("Will you add something?");
        }
    }

    // Get array of ids in favorites
    private function getUserFavorites() {
        $user = $this->db->users->findOne( array("username" => $this->user) );
        return $user["favorites"];
    }

    // Update favorites in DB
    private function updateFavorites($favs) {
        try {
            $this->db->users->update( array("username" => $this->user), array('$set' => array("favorites" => array_values($favs)) ) );
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    // Rate up or down report
    private function rate($report, $action) {
        if ($action === "up"){
            try {
                $this->db->reports->update( 
                        array("_id" => $report["_id"]), 
                        array('$set' => array("rate" => ($report["rate"] + 1) ) )
                );
                return 1;
            } catch (Exception $e) {
                return 0;
            }
        
        } else {
            try {
                $this->db->reports->update( 
                        array("_id" => $report["_id"]), 
                        array('$set' => array("rate" => ($report["rate"] - 1) ) )
                );
                return 1;
            } catch (Exception $e) {
                return 0;
            }
        }
    }

}

?>
