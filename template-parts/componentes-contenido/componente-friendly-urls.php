<?php 
$term = get_queried_object();

/* Mostramos las urls de las categorías que cuelgan de esta */
$children = get_terms( $term->taxonomy, array(
    'parent'    => $term->term_id,
    'hide_empty' => false
) );

/* Si esta categoría no tiene hijos, mostramos sus hermanas */
if(!$children && $term->parent > 0){
    $children = get_terms( $term->taxonomy, array(
        'parent'    => $term->parent,
        'hide_empty' => false
    ) );
}

$subcategorias = array();
$zonas = array();
foreach ($children as $cat){
    if (get_field('categoria_zona', $cat)){
        array_push($zonas, $cat);
    }else{
        array_push($subcategorias, $cat);
    }
}

if (sizeof($children)>0){
?>
<section class="friendly_urls_section container">
    <h3 class="titulo_friendl_urls">
        <?= __("Podría serte") ?>
        <span class="highligted"><?= __("útil") ?></span>
    </h3>
    <div class="row">
        <div class="friendly_urls_list_wrapper col-md-6">
            <p class="link_list_title"><?=__("No te puedes perder")?></p>
            <ul class="friendy_urls_list row">

                <?php foreach ($subcategorias as $tax): ?>
                    <li class="col-md-6">
                        <a href="<?=get_term_link($tax->term_id)?>">
                            <?=$tax->name?>
                        </a>
                    </li>
                <?php endforeach; ?>

            </ul>
        </div>

        <div class="friendly_urls_list_wrapper col-md-6">
            <p class="link_list_title"><?=__("Zonas más visitadas")?></p>
            <ul class="friendy_urls_list row">

                <?php foreach ($zonas as $tax): ?>
                    <li class="col-md-6">
                        <?php $url_generada = generate_friendly_url_for_zonecategory($tax); ?>
                        <a href="<?=$url_generada?>">
                            <?=$tax->name?>
                        </a>
                    </li>
                <?php endforeach; ?>

            </ul>
        </div>

    </div>
</section>
<?php } ?>
