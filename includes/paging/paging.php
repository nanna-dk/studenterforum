<?php
// ############### pagination function #########################################
function paginate($item_per_page, $current_page, $total_records, $total_pages) {
    $pagination = '';
    if ($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages) { //verify total pages and current page number
        $pagination.= '<ul class="pagination justify-content-end">';
        $right_links = $current_page + 3;
        $previous = $current_page - 1; //previous link
        $next = $current_page + 1; //next link
        $first_link = true; //boolean var to decide our first link
        if ($current_page > 1) {
            $previous_link = ($previous == 0) ? 1 : $previous;
            //$pagination.= '<li class="class="page-item first"><a class="page-link" href="#" data-page="1" title="First">&laquo;</a></li>'; //first link

            $pagination.= '<li class="page-item"><a class="page-link" href="#" data-page="' . $previous_link . '" title="Tilbage">Tilbage</a></li>'; //previous link
            for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                if ($i > 0) {
                    $pagination.= '<li class="page-item"><a class="page-link" href="#" data-page="' . $i . '" title="Side ' . $i . '">' . $i . '</a></li>';
                }
            }
            //set first link to false
            $first_link = false;
        }
        if ($first_link) { //if current active page is first link
            $pagination.= '<li class="page-item disabled"><a class="page-link" href="#">Tilbage</a></li>';
            $pagination.= '<li class="page-item active"><a class="page-link" href="#">' . $current_page . '<span class="sr-only">(Aktiv)</span></a></li>';
        }
        elseif ($current_page == $total_pages) { //if it's the last active link
            $pagination.= '<li class="page-item active"><a class="page-link" href="#">' . $current_page . '<span class="sr-only">(Aktiv)</span></a></li>';
            $pagination.= '<li class="page-item disabled"><a class="page-link" href="#">Næste</a></li>';
        }
        else { //regular current link
            $pagination.= '<li class="page-item active"><a class="page-link" href="#">' . $current_page . '<span class="sr-only">(Aktiv)</span></a></li>';
        }
        for ($i = $current_page + 1; $i < $right_links; $i++) { //create right-hand side links
            if ($i <= $total_pages) {
                $pagination.= '<li class="page-item"><a class="page-link" href="#" data-page="' . $i . '" title="Side ' . $i . '">' . $i . '</a></li>';
            }
        }
        if ($current_page < $total_pages) {
            $next_link = (($current_page + 1) > $total_pages) ? $total_pages : $current_page + 1;
            $pagination.= '<li class="page-item"><a class="page-link" href="#" data-page="' . $next_link . '" title="Næste">Næste</a></li>'; //next link

            //$pagination.= '<li class="page-item last"><a class="page-link" href="#" data-page="' . $total_pages . '" title="Last">&raquo;</a></li>'; //last link
        }
        $pagination.= '</ul>';
    }
    //return pagination links
    return $pagination;
}
?>
