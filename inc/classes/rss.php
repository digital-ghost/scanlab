<?php

class Rss extends Scanlab {

	public function __construct() {
		parent::__construct();
		if (!isset($_SERVER['PHP_AUTH_USER'])) {
		    header('WWW-Authenticate: Basic realm="Enter your login/pass"');
		    header('HTTP/1.0 401 Unauthorized');
		    die('Error 401');
		} else {
			$user = (string) $_SERVER["PHP_AUTH_USER"];
			$password = (string) $_SERVER["PHP_AUTH_PW"];
			$hash = sha1(sha1($password) . AUTH_SALT);
			$this->user = $this->checkAuth($user, $hash, true);
			if (!$this->user) {
				showError("Wrong details");
			}
			if ($this->user["api_key"] != "1") {
				showError("You are not API user");
			}
		}

		$this->server_name = $_SERVER["SERVER_NAME"];
		if (!empty($_SERVER["HTTPS"])) {
			$this->protocol = "https";
		} else {
			$this->protocol = "http";
		}
	}

	public function GET($matches) {
		if (isset($matches[1]) && !empty($matches[1]) ) {
			$mode = (string) $matches[1];
			switch ($mode) {
				case "search":
					$q = getSearchQuery();
                	$db_query = queryFromSearchArray(queryToArray($q));
                    $vars["results"] = $this->db->reports->find($db_query)
                    	->sort(array("timestamp" => -1))->limit(20);
					$vars["description"] = "search feed";
					$vars["server_name"] = $this->server_name;
					$vars["protocol"] = $this->protocol;

					$this->output('application/xml');
					$this->view("rss.html", $vars);
					break;
				default:
					die("wrong mode");
			}
		}
	}

}

?>
