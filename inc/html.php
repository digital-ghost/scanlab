<?php
##
#  Functions for generating HTML
##

function showError($error) {
    require_once('inc/templates/error_page.php');
    die();
}

function redirect($url) {
    header("Location: ".$url);
    die();
}

// generate pagination HTML function
function pagination_init($vars){
    $pages = $vars['pages'];
    $current_page = $vars['current'];
    $b_u = $vars['base_url']; // sumthing liek /index,php?page=

    $html = '<div class="pagination">';

    if ($current_page > 1) {
        $html .= '<a class="btn" href="'.$b_u.'1">&lt;&lt; first</a> ';
        $html .= '<a class="btn" href="'.$b_u.($current_page - 1).'">&lt; prev</a> '; 
    }

        $html .= ' ' .$current_page. ' ';

    if ($current_page < $pages) {
        $html .= '<a class="btn" href="'.$b_u.($current_page + 1).'">next &gt;</a> '; 
    }

    if ($current_page != $pages ){
        $html .= '<a class="btn" href="'.$b_u.$pages.'">last &gt;&gt;</a>';
    }
    $html .= '</div>';
    return $html;
}

// generate xml for API
function genXML($result, $array) {
    //HEADER
    $xml = '<?xml version="1.0"?><?xml-stylesheet href="file:///usr/bin/../share/nmap/nmap.xsl" type="text/xsl"?><!-- Nmap 6.00 scan initiated Thu Jan 01 00:00:00 1970 as: nmap -iL targets.txt --><nmaprun scanner="nmap" args="nmap -iL targets.txt" start="0" startstr="Thu Jan 01 00:00:00 1970" version="6.00" xmloutputversion="1.04"><scaninfo type="connect" protocol="tcp" numservices="100" services="1-1024"/><verbose level="0"/><debugging level="0"/>';

    if ($array == false) {
        // SINGLE report to XML
        $xml .= $result['raw_xml'];
        $xml .= '<runstats><finished time="1" timestr="Thu Jan 01 00:00:00 1970" elapsed="1" summary="Nmap done at Thu Jan 01 00:00:00 1970; 1 IP address (1 host up) scanned in 1 seconds" exit="success"/><hosts up="1" down="0" total="1"/>
</runstats></nmaprun>';
    } else {
        // ARRAY of reports to XML
        $rnum = $result->count(); //total number of results
        foreach ($result as $row){
            $xml .= $row['raw_xml'];
        }
        $xml .= '<runstats><finished time="1" timestr="Thu Jan 01 00:00:00 1970" elapsed="1" summary="Nmap done at Thu Jan 01 00:00:00 1970; '.$rnum.' IP addresses ('.$rnum.' hosts up) scanned in 1 seconds" exit="success"/><hosts up="'.$rnum.'" down="0" total="'.$rnum.'"/></runstats></nmaprun>';
    }
    return $xml;
}

?>