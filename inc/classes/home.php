<?php
##
#  Home page
#  Show just some basic info about this site
##

class Home extends Scanlab {

    function GET() {
        if (!$this->checkLogin() && IS_PRIVATE === true) {
            redirect(REL_URL."auth/login");
        }
        $total = $this->db->reports->count(); // total number of entries
        $vars = array( 'total' => $total );
        $this->view('home.html', $vars);
    }

}
