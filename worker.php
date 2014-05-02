<?php
##
#	Worker
#	Run this file every 30 minutes
##
if (PHP_SAPI !== "cli") die("this should be run only via cli");
require("inc/settings.php");
require("inc/db.php");

$cli = new MongoClient(DB_SERVER);
$db = $cli->selectDB(DB_NAME);

if (isset($argv[1])){
    switch ($argv[1]) {
        case "update_cache":
            ##
            #   Update global distinct values
            ##
            updateOverviewCache($db);
            break;
        case "del_old":
            ##
            #   Delete old and unrated reports
            ##
            $db->reports->remove(array( 
                'timestamp' => array('$lt' => (time() - (60*60*24*21))),
                'rate' => 0
            ));
            //count each user results
            $users = $db->users->find(array('api_key' => 1));
            foreach ($users as $user) {
                $reports_count = $db->reports->count(array('user' => $user['username']));
                $db->users->update(
                    array('username'=>$user['username']),
                    array('$set' => array('reports_count' => $reports_count))
                );
            }
            break;
        case "create_indexes":
            $db->reports->ensureIndex(array(
                //'report.ports.portid' => 1,
                //'report.ports.service.name' => 1,
                //'report.ports.state' => 1,
                'timestamp' => -1,
                //'report.geoip.country' => 1,
                //'user' => 1,
                //'rate' => 1
            ), array("name" => "sl_global"));
            break;
        case "delete_indexes":
            $db->reports->deleteIndexes();
            break;
        default:
            die("Wrong action.\n");
            break;
    }
} else {
    die("No action specified. See documentation or script sources.\n");
}


?>