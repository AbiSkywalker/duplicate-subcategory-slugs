<?php
get_header();

$queried_category =  get_queried_object();

$term = get_queried_object();
if(get_field('categoria_zona', $term)){
    //si la categoría es una zona (ACF), cargamos la penúltima parte de la url para sacar la categoria padre
    $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri_segments = explode('/', $uri_path);
    $parent_category_slug = $uri_segments[sizeof($uri_segments)-3];

    $parent_category = get_term_by('slug', $parent_category_slug, 'product_cat');
    //Busco la categoría de zona correcta

    global $wpdb;
    $query_tax = $wpdb->prepare(
        "SELECT `term_id` FROM ".$wpdb->prefix."term_taxonomy 
                    WHERE `term_id` IN (
                        SELECT `term_id` FROM ".$wpdb->prefix."terms 
                            WHERE slug like %s
                    ) and `parent` = %d", $term->slug.'-%', $parent_category->term_id
    );
    $res = $wpdb->get_col( $query_tax);

    if($res){
        $queried_category = get_term_by('id', $res[0], 'product_cat');
        global $wp_query;
        $wp_query->queried_object = $queried_category;
    }

}
?>

get_footer();
