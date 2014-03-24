<?php
##
#  Basic functions
##

//prepare data for insertion
function prepareData($reports, $user) {
    $time = time();

    //decode string
    $reports = base64_decode($reports, true);
    if (!$reports) die('cant decode this base64');
    if (strlen($reports) > 1000000) die('your json is too long');

    $reports = json_decode($reports, true);
    if (!$reports || !is_array($reports)) die('wrong json string');
    if (count($reports) > 20) die("too many reports");
    
    $insert_data = array(); // THIS we will be returning!

    foreach ($reports as $report) {
        if ( empty($report) ) die('empty report');
        if (!$report['report']) die('bad string no report');
        if (!$report['raw_xml']) die('bad string no xml');
        if (strlen($report['raw_xml']) > 65000) die('your xml report is too big');
        $tags = array();
        if (isset($report['tags']) && is_array($report['tags']) ) {
            //validate tags
            if (!empty($report['tags'])) {
                foreach ($report['tags'] as $tag) {
                    if (!in_array($tag, $tags)) $tags[] = (string) $tag;
                }
            }
        }

        $report['report'] = validateReport($report['report']);
        $data = array(
            "raw_xml" => (string) $report['raw_xml'], // raw xml output "<host> ... </host>"
            "report" => $report['report'], // some parsed info about host
            "user" => $user, // username 
            "timestamp" => $time, // when added
            "tags" => $tags, // tags for classify results
            "rate" => 0 // rating of record (for deletion and ranking)
        );
        $insert_data[] = $data;
    }
    return $insert_data;
}

//get page variable (or set to 1)
function getPage() {
    if (isset($_GET['p']) && !empty($_GET['p'])) {
        $page = $_GET['p'];
        if (preg_match('/[^0-9]+/', $page)) showError('you fail');
        if ($page < 1) showError('you fail');
        $page = (int) $page;
    } else {
        $page = 1;
    }

    if (PROMO_MODE && $page > 2 && !isset($_SESSION["username"])) {
        showError("Register to get all advanced features of ScanLab!");
    }

    return $page;
}

//get serach query
function getSearchQuery() {
    if (isset($_GET['q']) && !empty($_GET['q'])) {
        $q = urldecode( (string) $_GET['q'] );
        if (preg_match('/[^0-9a-zA-Z\ \:\-\"\,\.\_]+/', $q)) showError('illegal characters in your query');
        if (strlen($q) < 3 || strlen($q) > 140) showError('Search query must be from 3 to 140 symbols.');
        return $q;
    } else {
        showError("Type something in the search box");
    }
}

// function to validate input report
function validateReport($report) {

    if ( !is_array($report)) die("report must be an array!");

    if ( !isset($report['status']) || empty($report['status']) ) die('host status not set in report');
    if ( !isset($report['address']) || empty($report['address']) ) die('ip not set in report');
    if ( !isset($report['hostname']) ) die('hostnames not set in report');
    if ( !isset($report['geoip']) || !isset($report['geoip']['country']) ) die('geoip not set in report');

    $valid_report['status'] = (string) $report['status'];
    $valid_report['address'] = (string) $report['address'];
    $valid_report['hostname'] = (string) $report['hostname'];
    $valid_report['geoip']['country'] = (string) $report['geoip']['country'];

    if ( !isset($report['ports']) || empty($report['ports']) || !is_array($report['ports']) ) 
        die('ports not set in report');

    foreach ($report['ports'] as $port) {
        //blah blah filling return ports
        if (!is_array($port)) die('invalid port');

        if (!isset($port['portid']) || empty($port['portid'])) die('invalid port 0');
        if (!isset($port['state']) || empty($port['state'])) die('invalid port 1');
        if (!isset($port['protocol']) || empty($port['protocol'])) die('invalid port 2');
        if (!isset($port['service']) || !isset($port['service']['name']) || empty($port['service']['name'])) 
            die('invalid port 2');

        $valid_port['portid'] = (string) $port['portid'];
        $valid_port['state'] = (string) $port['state'];
        $valid_port['protocol'] = (string) $port['protocol'];
        $valid_port['service']['name'] = (string) $port['service']['name'];
        $valid_ports[] = $valid_port;
    }

    $valid_report['ports'] = $valid_ports;
    return $valid_report;
}

?>
