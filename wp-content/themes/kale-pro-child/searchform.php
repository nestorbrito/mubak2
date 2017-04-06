<?php
/**
 * The template for displaying the search form
 * Customizado para el buscador del header left
 *
 * @package kale
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url( '/' )); ?>">
    <div class="form-group form-inline">
        <input placeholder="¿Qué estás buscando?" type="search" class="search-field form-control" value="<?php echo get_search_query() ?>" name="s" />
        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
    </div>

</form>