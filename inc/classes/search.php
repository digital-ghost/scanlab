<?php
class Search extends Scanlab {

    function GET() {
        // forbid search for anonymous users if private
        if (!$this->checkLogin() && IS_PRIVATE === true) {
            redirect(REL_URL.'auth/login');
        }

        $page = getPage();
        $q = getSearchQuery();

        $db_query = queryFromSearchArray(queryToArray($q));

        // results on page
        $limit = 10;

        $result_cursor = $this->db->reports->find($db_query, 
            array('report'=>true, 'user'=>true, 'rate'=>true, 'tags'=>true, 'timestamp'=>true));

        if (!$this->user || !isset($_SESSION['api_key']) || $_SESSION['api_key'] != "true") {
            $total = $result_cursor->limit(10000)->count(true);
        } else {
            $total = $result_cursor->limit(100000)->count(true);
        }

        if ($total == 0) $this->renderError('I found nothing!');
        // last page number
        $pages_index = ceil($total / $limit);

        if ($page > $pages_index) $this->renderError('you fail');

        $skip = ($page * $limit) - $limit;

        if (SORT_SEARCH == true) {
            $results = $result_cursor->sort(array('timestamp' => -1))->skip($skip)->limit($limit);            
        } else {
            $results = $result_cursor->sort(array('$natural' => -1))->skip($skip)->limit($limit);
        }

        $pagination = pagination_init(array(
            "pages" => $pages_index,
            "current" => $page,
            "base_url" => REL_URL."search?q=".urlencode($q)."&p="
        ));

        //variables to pass to the template
        $vars = array(
            "search_query" => $q,
            "search_url_query" => urlencode($q),
            "total_results" => $total,
            "results"=>$results,
            "pagination" => $pagination
        );

        $this->view("results.html", $vars);

    }

}

?>
