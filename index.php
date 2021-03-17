<?php

/*
Plugin Name: CPT Peliculas
Plugin URL: http://novaprova.lndo.site/
Description: Custom post type
Author: Adriana Serrano
Version: 0.9
Author URL: http://novaprova.lndo.site/
*/

//Adding CarbonFields
use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
  require_once( 'vendor/autoload.php' );
  \Carbon_Fields\Carbon_Fields::boot();
}

//Adding thumbnail
function my_theme_setup() {
  add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'my_theme_setup' );

//Creating custom post type: Peliculas
add_action( 'init', 'crear_cpt' );
function crear_cpt() {
  $args = array(
    'public' => true,
    'label' => 'Peliculas',
    'supports' => array( 'title', 'thumbnail' ),
  );
  register_post_type( 'peliculas', $args );
}

//Adding custom fields: sinopsis y fecha
add_action( 'carbon_fields_register_fields', 'info_pelicula' );
function info_pelicula() {
  Container::make( 'post_meta', 'Informacion' )
  ->where( 'post_type', '=', 'peliculas' )
  ->add_fields( array(
    Field::make( 'rich_text', 'crb_sinopsis', 'Sinopsis' ),
    Field::make( 'date', 'crb_fecha_pelicula', 'Fecha de estreno' )
  ));
}

//Adding custom fields for actor's taxonomy: foto i fecha de nacimiento
add_action( 'carbon_fields_register_fields', 'info_actores' );
function info_actores() {
  Container::make( 'term_meta', __( 'Informacion' ) )
  ->where( 'term_taxonomy', '=', 'actores' )
  ->add_fields( array(
    Field::make( 'image', 'crb_foto', 'Fotografia' ),
    Field::make( 'date', 'crb_fecha_actor', 'Fecha de nacimiento' )
  ) );
}


//Creating taxonomies
function crear_taxonomias() {

  //Géneros
  $etiquetas_generos = array(
    'name' => __( 'Géneros' ),
    'singular_name' => __( 'Género' ),
    'search_items' =>  __( 'Buscar géneros' ),
    'all_items' => __( 'Todos los géneros' ),
    'parent_item' => __( 'Género padre' ),
    'parent_item_colon' => __( 'Género padre:' ),
    'edit_item' => __( 'Editar género' ),
    'update_item' => __( 'Actualizar género' ),
    'add_new_item' => __( 'Agregar un nuevo género' ),
    'menu_name' => __( 'Géneros' ),
  );

  //Actores
  $etiquetas_actores = array(
    'name' => __( 'Actores' ),
    'singular_name' => __( 'Actor' ),
    'search_items' =>  __( 'Buscar actores' ),
    'all_items' => __( 'Todos los actores' ),
    'parent_item' => __( 'Actor padre' ),
    'parent_item_colon' => __( 'Actor padre:' ),
    'edit_item' => __( 'Editar actor' ),
    'update_item' => __( 'Actualizar actor' ),
    'add_new_item' => __( 'Agregar un nuevo actor' ),
    'menu_name' => __( 'Actores' ),
  );

  //Registering taxonomy: generos
  register_taxonomy(
    'generos',
    array('peliculas'),
    array(
      'hierarchical' => true, 
      'labels' => $etiquetas_generos,
      'show_ui' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'genero' ),
    )
  );

  //Registering taxonomy: actores
  register_taxonomy(
    'actores',
    array('peliculas'),
    array(
      'hierarchical' => true,
      'labels' => $etiquetas_actores, 
      'show_ui' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'actor' ),
    )
  );
}
add_action( 'init', 'crear_taxonomias', 0 );
