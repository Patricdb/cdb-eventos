<?php
// Evitar el acceso directo al archivo.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Shortcode para mostrar el formulario de inscripción en un evento.
 *
 * Uso: [cdb_evento_inscripcion]
 */
function cdb_evento_inscripcion_shortcode( $atts ) {
    global $post;

    // Asegurarse de que estamos en un post del tipo 'evento'
    if ( 'evento' !== $post->post_type ) {
        return '';
    }

    // Verificar si el usuario está logueado.
    if ( ! is_user_logged_in() ) {
        return '<p>Debes iniciar sesión para inscribirte en este evento.</p>';
    }

    $user_id = get_current_user_id();
    $inscripciones = get_post_meta( $post->ID, '_cdb_eventos_inscripciones', true );
    if ( ! is_array( $inscripciones ) ) {
        $inscripciones = array();
    }

    // Verificar si el usuario ya está inscrito.
    if ( in_array( $user_id, $inscripciones ) ) {
        return '<p>Ya estás inscrito en este evento.</p>';
    }

    // Procesar la inscripción al recibir el formulario.
    // Nota: En esta versión simplificada, solo se muestra un mensaje de confirmación en pantalla,
    // sin enviar ningún correo electrónico.
    if ( isset( $_POST['cdb_evento_inscripcion_nonce'] ) && wp_verify_nonce( $_POST['cdb_evento_inscripcion_nonce'], 'cdb_evento_inscripcion' ) ) {
        // Agregar el ID del usuario a la lista de inscripciones.
        $inscripciones[] = $user_id;
        update_post_meta( $post->ID, '_cdb_eventos_inscripciones', $inscripciones );
        return '<p>¡Te has inscrito correctamente en el evento!</p>';
    }

    // Mostrar el formulario de inscripción.
    ob_start();
    ?>
    <form method="post">
        <?php wp_nonce_field( 'cdb_evento_inscripcion', 'cdb_evento_inscripcion_nonce' ); ?>
        <input type="submit" value="Inscribirse en el evento">
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode( 'cdb_evento_inscripcion', 'cdb_evento_inscripcion_shortcode' );

/**
 * Agregar un meta box en el administrador para visualizar las inscripciones.
 */
function cdb_eventos_inscripciones_meta_box() {
    add_meta_box(
        'cdb_eventos_inscripciones',                // ID del meta box
        __( 'Inscripciones al Evento', 'cdb-eventos' ), // Título
        'cdb_eventos_inscripciones_meta_box_callback', // Función de renderizado
        'evento',                                    // Post type
        'side',                                      // Contexto: side (barra lateral)
        'default'
    );
}
add_action( 'add_meta_boxes', 'cdb_eventos_inscripciones_meta_box' );

/**
 * Callback para renderizar el meta box de inscripciones.
 *
 * @param WP_Post $post Objeto del post actual.
 */
function cdb_eventos_inscripciones_meta_box_callback( $post ) {
    $inscripciones = get_post_meta( $post->ID, '_cdb_eventos_inscripciones', true );
    if ( ! is_array( $inscripciones ) || empty( $inscripciones ) ) {
        echo '<p>No hay inscripciones para este evento.</p>';
        return;
    }
    echo '<ul>';
    foreach ( $inscripciones as $user_id ) {
        $user_info = get_userdata( $user_id );
        if ( $user_info ) {
            echo '<li>' . esc_html( $user_info->display_name ) . ' (' . esc_html( $user_info->user_email ) . ')</li>';
        }
    }
    echo '</ul>';
}

/**
 * Inyectar el shortcode [cdb_evento_inscripcion] en el contenido
 * del CPT "evento" de forma automática.
 */
function cdb_eventos_inyectar_shortcode_en_evento( $content ) {
    // Verificar que estamos en la pantalla individual de un CPT "evento".
    if ( is_singular( 'evento' ) && in_the_loop() && is_main_query() ) {
        // Puedes elegir si deseas que aparezca antes o después del contenido.
        // Aquí lo añadimos al final del contenido.
        $content .= do_shortcode('[cdb_evento_inscripcion]');
    }
    return $content;
}
add_filter( 'the_content', 'cdb_eventos_inyectar_shortcode_en_evento' );

