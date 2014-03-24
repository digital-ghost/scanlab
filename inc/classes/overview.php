<?php
##
#  Services file
#  Show distinct services
##
class Overview extends Scanlab {
    function __construct() {
        parent::__construct();
        if (!$this->user) redirect(REL_URL."auth/login");
    }

    function GET($matches) {
        if (USE_WORKER === true) {
            $distinct = $this->db->cache->findOne(array("key" => "distinct_cache"));
            if ($distinct == NULL) showError("no cached version available");
            $distinct = unserialize($distinct["value"]);   
        } else {
            $distinct = getDistinct($this->db);
        }

        if (isset($matches[2]) && !empty($matches[2])) {
            $type = $matches[2];

            if ($type === 'services') {
                //$service_distinct = $this->db->reports->distinct('report.ports.service.name');
                $vars = array("services" => $distinct["services"]);
            } elseif ($type === 'ports') {
                $vars = array("ports" => $distinct["ports"]);
            } elseif ($type==='countries') {
                $vars = array("countries" => $distinct["countries"]);
            } elseif ($type==='tags') {
                $vars = array("tags" => $distinct["tags"]);
            } else {
                //$vars = array();
                $this->renderError('no such type');
            }
        } else {
            $vars = array();
        }

        $this->view('overview.html', $vars);

    }

}

?>
