<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function cdb_eventos_get_mensajes_default() {
    return array(
        'login_requerido' => array(
            'texto'     => __( 'Debes iniciar sesión para inscribirte en este evento.', 'cdb-eventos' ),
            'secundario'=> '',
            'tipo'      => 'aviso',
            'mostrar'   => true,
        ),
        'ya_inscrito' => array(
            'texto'     => __( 'Ya estás inscrito en este evento.', 'cdb-eventos' ),
            'secundario'=> '',
            'tipo'      => 'info',
            'mostrar'   => true,
        ),
        'inscripcion_ok' => array(
            'texto'     => __( '¡Te has inscrito correctamente en el evento!', 'cdb-eventos' ),
            'secundario'=> '',
            'tipo'      => 'exito',
            'mostrar'   => true,
        ),
        'error_generico' => array(
            'texto'     => __( 'Ha ocurrido un error. Por favor, inténtalo de nuevo más tarde.', 'cdb-eventos' ),
            'secundario'=> '',
            'tipo'      => 'error',
            'mostrar'   => true,
        ),
        'sin_eventos' => array(
            'texto'     => __( 'No hay eventos disponibles.', 'cdb-eventos' ),
            'secundario'=> '',
            'tipo'      => 'info',
            'mostrar'   => true,
        ),
        'evento_finalizado' => array(
            'texto'     => __( 'El evento ya ha finalizado.', 'cdb-eventos' ),
            'secundario'=> '',
            'tipo'      => 'aviso',
            'mostrar'   => true,
        ),
        'sin_inscripciones' => array(
            'texto'     => __( 'No hay inscripciones para este evento.', 'cdb-eventos' ),
            'secundario'=> '',
            'tipo'      => 'info',
            'mostrar'   => true,
        ),
    );
}

function cdb_eventos_get_mensajes() {
    $defaults = cdb_eventos_get_mensajes_default();
    $saved    = get_option( 'cdb_eventos_mensajes', array() );
    return wp_parse_args( $saved, $defaults );
}

function cdb_eventos_get_tipos_color_default() {
    return array(
        'info' => array(
            'nombre'      => __( 'Información', 'cdb-eventos' ),
            'clase'       => 'info',
            'color'       => '#46bfe2',
            'color_texto' => '#0a4b78',
        ),
        'exito' => array(
            'nombre'      => __( 'Éxito', 'cdb-eventos' ),
            'clase'       => 'exito',
            'color'       => '#46b450',
            'color_texto' => '#1e5220',
        ),
        'aviso' => array(
            'nombre'      => __( 'Aviso', 'cdb-eventos' ),
            'clase'       => 'aviso',
            'color'       => '#ffb900',
            'color_texto' => '#604400',
        ),
        'error' => array(
            'nombre'      => __( 'Error', 'cdb-eventos' ),
            'clase'       => 'error',
            'color'       => '#dc3232',
            'color_texto' => '#760000',
        ),
    );
}

function cdb_eventos_get_tipos_color() {
    $defaults = cdb_eventos_get_tipos_color_default();
    $saved    = get_option( 'cdb_eventos_tipos_color', array() );
    return wp_parse_args( $saved, $defaults );
}

function cdb_eventos_get_mensaje_text( $clave ) {
    $mensajes = cdb_eventos_get_mensajes();
    return isset( $mensajes[ $clave ]['texto'] ) ? $mensajes[ $clave ]['texto'] : '';
}

function cdb_eventos_get_mensaje( $clave ) {
    $mensajes = cdb_eventos_get_mensajes();
    if ( ! isset( $mensajes[ $clave ] ) ) {
        return '';
    }
    $mensaje = $mensajes[ $clave ];
    if ( empty( $mensaje['mostrar'] ) ) {
        return '';
    }
    $tipo  = isset( $mensaje['tipo'] ) ? $mensaje['tipo'] : 'info';
    $texto = cdb_eventos_get_mensaje_text( $clave );
    $sec   = isset( $mensaje['secundario'] ) ? $mensaje['secundario'] : '';
    $html  = '<div class="cdb-eventos-mensaje cdb-eventos-' . esc_attr( $tipo ) . '">';
    $html .= wp_kses_post( $texto );
    if ( $sec ) {
        $html .= ' <span class="mensaje-secundario">' . wp_kses_post( $sec ) . '</span>';
    }
    $html .= '</div>';
    return $html;
}

function cdb_eventos_get_mensaje_js( $clave ) {
    return esc_js( cdb_eventos_get_mensaje_text( $clave ) );
}
