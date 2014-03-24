<?php
##
#   Urls config
##
$conf_urls = array(
    "" => "Home",
    "api/(insert|iplist|get_target|xml_export|xml_id)(.*)" => "Api",
    "overview(|/([a-z]+))" => "Overview",
    "id/([0-9a-zA-Z]+)" => "Report",
    "search\?(.*)" => "Search",
    "auth(|/([a-z]+))" => "Auth",
    "root(|/([a-z]+))" => "Root",
    "user(|/([a-z_]+)(|\?p=([0-9]+)))" => "User",
    "forum(|/([a-z_]+)(|/([a-z0-9]+))(|\?p=([0-9]+)))" => "forum",
    "ajax" => "Ajax",
    "rss/(search)?(.*)" => "Rss",
    "statique/(js|captcha|)" => "Statique"
);


##
#   Relative URL support
##
$urls = array();
foreach ( array_keys($conf_urls) as $key ) {
    $urls[REL_URL.$key] = $conf_urls[$key];
}

?>
