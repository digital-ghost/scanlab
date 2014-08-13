<?php
##
#  Search functions
##

// convert user input to array
function queryToArray($q) {
    $q_array = array(); // array we will return

    $phrases_count = preg_match_all('/"([a-zA-Z0-9\ \.\-\_]+)"/', $q, $phrases); // gets frases "router lol"
    // prepare phrases
    if ($phrases_count > 0 && $phrases) {
        $q = str_replace($phrases[0], "", $q); // cut phrases from query string!
        $q_array["phrases"] = array_unique($phrases[1]);
    }

    $terms_array = preg_split('/ /', $q); // get array of other terms

    $words = preg_grep('/^([a-z0-9A-Z\.\-\_]+)$/', $terms_array); // gets array of simple words
    $ports = preg_grep('/^port:([0-9\,]+)$/', $terms_array); // gets array of port:80,90 etc
    $tags = preg_grep('/^tag:([0-9a-z\,]+)$/', $terms_array); 
    $countries = preg_grep('/^country:([A-Z0-9\,]+)$/', $terms_array); 
    $services = preg_grep('/^service:([0-9a-zA-Z,\-:]+)$/', $terms_array); 
    $ips = preg_grep('/^ip:([0-9\.]+)$/', $terms_array); 
    $rated = preg_grep('/^rated:(yes|no)$/', $terms_array); 
    $users = preg_grep('/^user:([a-z0-9]+)$/', $terms_array); 

    //prepare words
    if ($words) {
        $q_array["words"] = array_unique($words);
    }
    // prepare ports
    if ($ports) {
        $ports = substr(current($ports), 5); // only 1 ports query available && cut port:
        $q_array["ports"] = array_unique( explode(",", $ports) );
    }
    // prepare tags
    if ($tags) {
        $tags = substr(current($tags), 4); // only 1 tags query available && cut
        $q_array["tags"] = array_unique( explode(",", $tags) );
    }
    // prepare countries
    if ($countries) {
        $countries = substr(current($countries), 8); 
        $q_array["countries"] = array_unique( explode(",", $countries) );
    }
    // prepare services
    if ($services) {
        $services = substr(current($services), 8); 
        $q_array["services"] = array_unique(explode(",", $services));
    }
    // prepare ips
    if ($ips) {
        $ip = substr(current($ips), 3); 
        $q_array["ip"] = $ip;
    }
    //prepare rated
    if ($rated) {
        $rated = substr(current($rated), 6);
        $q_array["rated"] = $rated;
    }
    //prepare user
    if ($users) {
        $user = substr(current($users), 5);
        $q_array["user"] = $user;
    }

    if (empty($q_array)){
        showError('You are doing it wrong');
    }

    return $q_array;
}

?>
