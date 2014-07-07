<?php
##
#  DB - database functions
##

// get db query from seach array 
function queryFromSearchArray($array){
    $query = array();

    if (isset($array['ports'])) {
        foreach ($array['ports'] as $port) {
            $query['$and'][] = array("report.ports" => array('$elemMatch' => array('state' => 'open', 'portid' => $port)));
        }
    }

    if (isset($array['countries'])) {
        $query['$and'][] = array("report.geoip.country" => array('$in' => $array['countries']));
    }

    if (isset($array['ip'])) {
        $query['$and'][] = array("report.address" => $array['ip']);
    }

    if (isset($array['services'])) {
        foreach ($array['services'] as $service) {
            $query['$and'][] = array("report.ports" => array('$elemMatch' => array('state' => 'open', 'service.name' => $service)));
        }
    }

    if (isset($array['tags'])) {
        foreach ($array['tags'] as $tag) {
            $query['$and'][] = array("tags" => $tag);
        }
    }

    if (isset($array['rated'])) {
        if ($array['rated'] == 'yes') {
            $query['$and'][] = array("rate" => array('$gt' => 0));
        } else {
            $query['$and'][] = array("rate" => 0);
        }
    }

    if (isset($array['user'])) {
        $query['$and'][] = array("user" => $array['user']);
    }

    // fill with word queries if any
    if (isset($array['words'])) {
        foreach ($array['words'] as $word) {
            if ($word[0] == "-") {
                $word = str_replace(array(".", "-"), array("\.", "\-"), substr($word,1));
                $query['$nor'][] = array("raw_xml" => new MongoRegex('/'.$word.'/i'));
            } else {
                $word = str_replace(array(".", "-"), array("\.", "\-"), $word);
                $query['$and'][] = array("raw_xml" => new MongoRegex('/'.$word.'/i'));
            }
        }
    }

    //phrases
    if (isset($array['phrases'])) {
        foreach ($array['phrases'] as $phrase) {
            if ($phrase[0] == "-") {
                $phrase = str_replace(array(".", "-"), array("\.", "\-"), substr($phrase, 1));
                $query['$nor'][] = array("raw_xml" => new MongoRegex('/'.$phrase.'/'));
            } else {
                $phrase = str_replace(array(".", "-"), array("\.", "\-"), $phrase);
                $query['$and'][] = array("raw_xml" => new MongoRegex('/'.$phrase.'/'));
            }
        }
    }

    return $query;
}

function getDistinct($db) {
    $distinct = array(); // we will save this to db

    $services = $db->reports->distinct('report.ports.service.name');
    foreach ($services as $service) {
        $count = $db->reports->find(array("report.ports" => array('$elemMatch' => 
            array('state' => 'open', 'service.name' => $service)
        )))->count();
        $distinct["services"][] = array("name" => $service, "count" => $count);
    }

    $ports = $db->reports->distinct('report.ports.portid');
    foreach ($ports as $port) {
        $count = $db->reports->find(array("report.ports" => array('$elemMatch' => 
            array('state' => 'open', 'portid' => $port)
        )))->count();
        $distinct["ports"][] = array("name" => $port, "count" => $count);
    }

    $countries = $db->reports->distinct('report.geoip.country');
    foreach ($countries as $country) {
        $count = $db->reports->find(array("report.geoip.country" => $country))->count();
        $distinct["countries"][] = array("name" => $country, "count" => $count);
    }

    $tags = $db->reports->distinct('tags');
    foreach ($tags as $tag) {
        $count = $db->reports->find(array("tags" => $tag))->count();
        $distinct["tags"][] = array("name" => $tag, "count" => $count);
    }
    return $distinct;
}

function updateOverviewCache($db) {
    $distinct = serialize(getDistinct($db));

    $cache = $db->cache->findOne(array("key" => "distinct_cache"));

    if ($cache == NULL) {
        // no cache available, insert new cache result
        $db->cache->insert(array("key" => "distinct_cache", "value" => $distinct));
    } else {
        // update cache
        $db->cache->update(array("key" => "distinct_cache"), array('$set' => array("value"=> $distinct)));
    }
}

function updateUserReportCount($user, $db) {
    $new_count = $db->reports->find(array('user'=>$user))->count();
    $db->users->update(
        array("username" => $user), array('$set' => array("reports_count" => $new_count))
    );
}

?>
