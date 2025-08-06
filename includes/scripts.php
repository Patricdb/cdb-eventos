<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function cdb_eventos_enqueue_scripts() {
    wp_enqueue_style( 'cdb-eventos', CDB_EVENTOS_URL . 'assets/cdb-eventos.css', array(), '1.0' );
    wp_enqueue_style( 'cdb-eventos-config-mensajes', CDB_EVENTOS_URL . 'assets/css/config-mensajes.css', array(), '1.0' );
    $tipos = cdb_eventos_get_tipos_color();
    $css = '';
    foreach ( $tipos as $slug => $tipo ) {
        $bg = isset( $tipo['color'] ) ? $tipo['color'] : '#fff';
        $color = isset( $tipo['color_texto'] ) ? $tipo['color_texto'] : '#000';
        $css .= '.cdb-eventos-mensaje.cdb-eventos-' . esc_attr( $slug ) . '{background:' . esc_attr( $bg ) . ';color:' . esc_attr( $color ) . ';}';
    }
    if ( $css ) {
        wp_add_inline_style( 'cdb-eventos-config-mensajes', $css );
    }
    wp_enqueue_script( 'cdb-eventos-mensajes', CDB_EVENTOS_URL . 'assets/js/mensajes.js', array(), '1.0', true );
    wp_localize_script( 'cdb-eventos-mensajes', 'cdb_eventos_mensajes', cdb_eventos_get_mensajes_for_js() );
}
add_action( 'wp_enqueue_scripts', 'cdb_eventos_enqueue_scripts' );

function cdb_eventos_get_mensajes_for_js() {
    $mensajes = cdb_eventos_get_mensajes();
    $out = array();
    foreach ( $mensajes as $clave => $mensaje ) {
        $out[ $clave ] = $mensaje['texto'];
    }
    return $out;
}
