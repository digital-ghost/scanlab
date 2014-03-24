<?php
##
#	AJAX interface
##

class Ajax extends Scanlab {

    function __construct() {
        parent::__construct();
        if (!$this->checkLogin() && IS_PRIVATE === true) {
            redirect(REL_URL."auth/login");
        }
    }

	function POST() {

		if (isset($_POST["action"]) && !empty($_POST["action"])) {
			$action = (string) $_POST["action"];
			switch($action) {
				case "get_last":
					$this->getLast();
					break;
				default:
					die("wtf? O_O");
			}

		}
	}

	private function getLast() {
		if (!isset($_POST["update"])) {
			// this will only output 10 last results
			$reports = $this->db->reports->find()->sort(array("timestamp" => -1))->limit(10);

			echo json_encode(iterator_to_array($reports));
		} else {
			$time = (int) $_POST["update"]; 
			$reports = $this->db->reports->find(array(
					"timestamp" => array('$gt' => $time),
				))
				->limit(100);

			echo json_encode(iterator_to_array($reports));
		}

	}
}
?>