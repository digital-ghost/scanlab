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
            if ($distinct == NULL) {
                if ($this->user === ROOT_USER) {
                    showError("No cached version available. You can manually update cache at <a href='".REL_URL
                        ."root/stats'>root panel</a>.");
                }
                showError("No cached version available.");
            }
            $distinct = unserialize($distinct["value"]);   
        } else {
            $distinct = getDistinct($this->db);
        }

        $vars = $distinct;
        $vars["total_count"] = $this->db->reports->count();
        
        $this->view('overview.html', $vars);

    }

}

?>
