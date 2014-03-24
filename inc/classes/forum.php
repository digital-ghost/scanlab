<?php
class Forum extends Scanlab {
    function __construct() {
        parent::__construct();
        if (!$this->user) redirect(REL_URL."auth/login");
    }

    function GET($matches) {
        if (isset($matches[2])) {
            switch ($matches[2]) {
                case "view":
                    $page = getPage();
                    $limit = 10;
                    $total = $this->db->comments->find(array("parent" => "0"))->count();

                    if ($total == 0) {
                        // render only post creation form
                        $this->view("forum/view.html");
                        return true;
                    }

                    // last page number
                    $pages_index = ceil($total / $limit);

                    if ($page > $pages_index) $this->renderError('you fail');

                    $skip = ($page * $limit) - $limit;
                    $vars['comments'] = $this->db->comments->find(array("parent"=> "0"))
                        ->sort(array('timestamp' => -1))->skip($skip)->limit($limit);

                    $vars['pagination'] = pagination_init(array(
                        "pages" => $pages_index,
                        "current" => $page,
                        "base_url" => REL_URL."forum/view?p="
                    ));

                    $this->view("forum/view.html", $vars);
                    break;
                case "thread":
                    // toot thread
                    if (isset($matches[4])) {
                        $id = (string) $matches[4];
                        $parent = $this->checkThread($id);
                        $vars["comments"] = $this->db->comments->find(array(
                            '$or' => array(
                                array("_id" => new MongoId($id)),
                                array("parent" => $id)
                            )
                        ));
                        $vars["parent"]["id"] = $parent["_id"];
                        $vars["parent"]["report"] = $parent["report"];
                        $this->view("forum/view.html", $vars);
                    }


                    break;
                default:
                    redirect(REL_URL."user/panel");
            }
        } else {
            redirect(REL_URL."forum/view");
        }
    }

    function POST($matches) {
        if (isset($matches[2])) {
            switch ($matches[2]) {
                case "add_comment":
                    // FORM to add comment
                    $this->add_comment();
                    break;
                default:
                    die("sasay");
            } 
        }
    }

    public function add_comment() {
        if (isset($_POST["text"]) && isset($_POST["parent"]) && isset($_POST["report"])) {

            $data = array(
                "user" => $this->user,
                "report" => (string) $_POST["report"],
                "text" => (string) $_POST["text"],
                "parent" => (string) $_POST["parent"],
                "timestamp" => time()
            );
            // security checks
            $this->checkFlood($data["timestamp"]);

            if ($data["report"] != "0") $this->checkReportId($data["report"]);            
            if ($data["parent"] != "0") { 
                $parent = $this->checkThread($data["parent"]);
                if ($data["report"] != $parent["report"]) showError("are you trying to cheat me?");
            } else {
                $data["replies"] = 0;
            }

            if (strlen($data["text"]) > 1000) showError("Your comment is too long. Max 1000");
            $data["_id"] = new MongoId();

            $this->db->comments->insert($data);

            // update parent post with replies count
            if ($data["parent"] != "0") $this->db->comments
                ->update(
                    array("_id" => new MongoId($data["parent"])),
                    array('$set' => array("replies" => $parent["replies"] + 1))
                );
            if ($data["report"] != "0") {
                redirect(REL_URL."id/".$data["report"]);
            } elseif ($data["parent"] != "0") {
                redirect(REL_URL."forum/thread/".$data["parent"]);
            } else {
                redirect(REL_URL."forum/thread/".$data["_id"]);
            }
        }
    }

    // flood check
    public function checkFlood($timestamp) {
        $post = $this->db->comments->find(array("user" => $this->user))
            ->sort(array("timestamp" => -1))->getNext();
        if ($timestamp - $post["timestamp"] < 30) showError("stop flooding!");
    }

    // check if thread exists
    public function checkThread($id) {
        if (preg_match('/[^0-9a-zA-Z]+/', $id)) $this->renderError('you fail');
        $result = $this->db->comments->findOne(array("_id" => new MongoId($id)));
        if (!$result) showError("This thread does not exist");
        if ($result["parent"] != "0") showError("Invalid thread");
        return $result;
    }

}

?>
