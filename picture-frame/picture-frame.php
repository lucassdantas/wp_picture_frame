<?php
/*
 * Plugin Name:       Picture Frame CPT
 * Plugin URI:        https://github.com/lucassdantas/wp_picture_frame
 * Description:       Adiciona CPT de quadros
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Lucas Dantas
 * Author URI:        linkedin.com/in/lucas-de-sousa-dantas/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

function picture_frame() {
    $labels = array(
        'name'               => 'Quadros',
        'singular_name'      => 'Quadro',
        'menu_name'          => 'Quadros',
        'name_admin_bar'     => 'Quadro',
        'add_new'            => 'Adicionar Novo',
        'add_new_item'       => 'Adicionar Novo Quadro',
        'new_item'           => 'Novo Quadro',
        'edit_item'          => 'Editar Quadro',
        'view_item'          => 'Ver Quadro',
        'all_items'          => 'Todos os Quadros',
        'search_items'       => 'Buscar Quadros',
        'parent_item_colon'  => 'Quadros Pai:',
        'not_found'          => 'Nenhum quadro encontrado.',
        'not_found_in_trash' => 'Nenhum quadro encontrado na lixeira.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'quadros' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'taxonomies'         => array(),
    );

    register_post_type( 'quadros', $args );
}
add_action( 'init', 'custom_post_type_quadros' );

// Adiciona campos personalizados ao tipo de post Quadros
function quadros_custom_fields() {
    add_meta_box(
        'quadros_fields',
        'Campos Personalizados',
        'quadros_fields_callback',
        'quadros',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'quadros_custom_fields' );

// Callback para exibir os campos personalizados
function quadros_fields_callback( $post ) {
    // Recupera os valores atuais dos campos, se existirem
    $codigo = get_post_meta( $post->ID, 'codigo', true );
    $tamanho = get_post_meta( $post->ID, 'tamanho', true );
    $moldura = get_post_meta( $post->ID, 'moldura', true );

    // Exibe os campos
    echo '<label for="codigo">Código:</label>';
    echo '<input type="text" id="codigo" name="codigo" value="' . esc_attr( $codigo ) . '" /><br>';

    echo '<label for="tamanho">Tamanho:</label>';
    echo '<input type="text" id="tamanho" name="tamanho" value="' . esc_attr( $tamanho ) . '" /><br>';

    echo '<label for="moldura">Com moldura:</label>';
    echo '<input type="checkbox" id="moldura" name="moldura" value="1" ' . checked( $moldura, 1, false ) . ' /><br>';
}

// Salva os valores dos campos personalizados
function save_quadros_custom_fields( $post_id ) {
    if ( isset( $_POST['codigo'] ) ) {
        update_post_meta( $post_id, 'codigo', sanitize_text_field( $_POST['codigo'] ) );
    }
    if ( isset( $_POST['tamanho'] ) ) {
        update_post_meta( $post_id, 'tamanho', sanitize_text_field( $_POST['tamanho'] ) );
    }
    $moldura = isset( $_POST['moldura'] ) ? 1 : 0;
    update_post_meta( $post_id, 'moldura', $moldura );
}
add_action( 'save_post', 'save_quadros_custom_fields' );
