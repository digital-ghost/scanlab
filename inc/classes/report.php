<?php
##
#  Show single report
#  Show report by id
##

class Report extends Scanlab {

    function GET($matches) {
        if (!$this->checkLogin() && IS_PRIVATE === true) {
            redirect(REL_URL.'auth/login');
        }
        
        // ID variable
        if (isset($matches[1]) && !empty($matches[1])) {
            $id = (string) $matches[1];
        } else {
            showError("no id set");
        }

        $result = $this->checkReportId($id);
        libxml_use_internal_errors(true);
        libxml_disable_entity_loader(true); // disable doctype injections
        if (preg_match('/<!DOCTYPE/i', $result['raw_xml'])) showError("XML contains something evil");

        $host = simplexml_load_string($result['raw_xml']);

        if (!$host) $this->renderError('invalid xml in this entry');
        //variables to pass to the template
        $vars = array(
            "id" => $id,
            "result" => $result,
            "host" => $host
        );

        $this->view("report.html", $vars);
    }
}


?>
