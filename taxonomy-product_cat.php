<?php
get_header();

//Comprobar si es una landing
if(lf_is_landing_url($_SERVER["REQUEST_URI"])) {

    lf_print_landing_template($_SERVER["REQUEST_URI"]);

}

else {
    $queried_category =  get_queried_object();
    
    $term = get_queried_object();
    if(get_field('categoria_zona', $term)){
        //si la categoría es una zona, cargamos la penúltima parte de la url para sacar la categoria padre
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
    get_template_part('template-parts/componentes-contenido/componente', 'h1-categoria-producto');
    ?>
    <div class="booking_search container">
        <form action="" class="search" id="booking_search_form">
            <fieldset>
                <div class="form-group">
                    <input type="text" name="booking_search_form-name" placeholder="Búsqueda">
                    <select name="booking_search_form-order" id="booking_search_form_order">
                        <option value="asc" ><?= __('Ascendiente', 'dokan-child') ?></option>
                        <option value="desc" selected><?= __('Descendiente', 'dokan-child') ?></option>
                    </select>
                    <select name="booking_search_form-order_by" id="booking_search_form_order_by">
                        <option value="title" selected><?= __('Título', 'dokan-child') ?></option>
                        <option value="date"><?= __('Fecha de publicación', 'dokan-child') ?></option>
                        <option value="modified"><?= __('Fecha de actualización', 'dokan-child') ?></option>
                    </select>
                    <input type="submit" value="Buscar">
                </div>
                <input type="hidden" name="taxonomy" value="<?= get_queried_object()->slug ?>">
            </fieldset>
        </form>
        <div class="results_and_sidebar">
            <?php lanzarote_booking_sidebar(get_queried_object()->slug) ?>
            <div class="search_results_wrapper">
                <div id="booking_loader" style="display: none;">
                    <span>Cargando...</span>
                </div>
                <div class="search_results">

                </div>
            </div>
        </div>
    </div>
    <?php
    get_template_part('template-parts/componentes-contenido/componente', 'bloque-pie-categoria-producto');
    get_template_part('template-parts/componentes-contenido/componente', 'friendly-urls');

}

get_footer();