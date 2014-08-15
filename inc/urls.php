<?php
##
#   Urls config
##
$conf_urls = array(
    "" => "Overview",
    "api/(login|insert|delete|iplist|get_target|xml_export|xml_id|upload_xml)(.*)" => "Api",
    "id/([0-9a-zA-Z]+)" => "Report",
    "search\?(.*)" => "Search",
    "auth(|/([a-z]+))" => "Auth",
    "root(|/([a-z]+))" => "Root",
    "user(|/([a-z_]+)(|\?p=([0-9]+)))" => "User",
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
