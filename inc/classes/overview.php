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
        $time_start = microtime(true);

        if (USE_WORKER === true) {
            $distinct = $this->db->cache->findOne(array("key" => "distinct_cache"));
            if ($distinct == NULL) {
                if ($this->user === ROOT_USER) {
                    $this->renderError("No cached version available. You can manually update cache at root panel.");
                }
                $this->renderError("No cached version available.");
            }
            $distinct = unserialize($distinct["value"]);   
        } else {
            $distinct = getDistinct($this->db);
        }

        function cmp($a, $b){
            return $b['count'] - $a['count'];
        }

        usort($distinct['ports'], 'cmp');
        usort($distinct['services'], 'cmp');
        usort($distinct['countries'], 'cmp');
        usort($distinct['tags'], 'cmp');
        $distinct["total_count"] = $this->db->reports->count();
        $this->view('overview.html', $distinct);
    }

}

?>
