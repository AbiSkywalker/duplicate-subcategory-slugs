<?php 
function get_product_category_by_name_and_parent($name = '', $parent = 0){

    global $wpdb;
    $query_tax = $wpdb->prepare(
        "SELECT `term_id` FROM ".$wpdb->prefix."term_taxonomy 
                    WHERE `term_id` IN (
                        SELECT `term_id` FROM ".$wpdb->prefix."terms 
                            WHERE name like %s
                    ) and `parent` = %d", $name, $parent
    );
    $res = $wpdb->get_col( $query_tax);

    if($res){
        $category = get_term_by('id', $res[0], 'product_cat');
        return $category;
    }
}

function generate_friendly_url_for_zonecategory($category){
    $url_generada = get_term_link($category->term_id);
    $array_url = explode('/', $url_generada);
    $ultimo_tramo = $array_url[sizeof($array_url)-2];
    $ultimo_tramo = substr($ultimo_tramo,0,strrpos($ultimo_tramo, '-'));
    //buscar cat que coincida con el slug obtenido
    $foundcat = get_term_by('slug', $ultimo_tramo, 'product_cat');
    if($foundcat){
        if($ultimo_tramo){
            $array_url[sizeof($array_url)-2] = $ultimo_tramo;
            $url_generada = implode('/', $array_url);
        }
    }   
    return $url_generada;
}
