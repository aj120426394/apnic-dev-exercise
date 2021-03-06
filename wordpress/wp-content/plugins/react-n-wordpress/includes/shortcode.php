<?php
// This file enqueues a shortcode.

defined( 'ABSPATH' ) or die( 'Direct script access disallowed.' );

add_shortcode( 'react_n_wordpress', function( $attrs ) {
    $default_attrs = array();
    $args = shortcode_atts( $default_attrs, $attrs );

    $filename = '';
    foreach ($attrs as $key => $attr) {
        if ($key === 'asset-manifest') {
            define( 'RNW_ASSET_MANIFEST', RNW_ASSET_MANIFEST_PATH . $attr );
            $filename = $attr;


        }
    }

    $asset_manifest = json_decode( file_get_contents(RNW_ASSET_MANIFEST_PATH . '/' . $filename), true )['files'];

    if ( isset( $asset_manifest[ 'main.css' ] ) ) {
        wp_enqueue_style( 'rnw', $asset_manifest[ 'main.css' ] );
    }

    wp_enqueue_script( 'rnw-runtime', $asset_manifest[ 'runtime-main.js' ], array(), null, true );

    wp_enqueue_script( 'rnw-main', $asset_manifest[ 'main.js' ], array('rnw-runtime'), null, true );

    foreach ( $asset_manifest as $key => $value ) {
        if ( preg_match( '@static/js/(.*)\.chunk\.js@', $key, $matches ) ) {
            if ( $matches && is_array( $matches ) && count( $matches ) === 2 ) {
                $name = "rnw-" . preg_replace( '/[^A-Za-z0-9_]/', '-', $matches[1] );
                wp_enqueue_script( $name,  $value, array( 'rnw-main' ), null, true );
            }
        }

        if ( preg_match( '@static/css/(.*)\.chunk\.css@', $key, $matches ) ) {
            if ( $matches && is_array( $matches ) && count( $matches ) == 2 ) {
                $name = "rnw-" . preg_replace( '/[^A-Za-z0-9_]/', '-', $matches[1] );
                wp_enqueue_style( $name, $value, array( 'rnw' ), null );
            }
        }
    }

    add_filter( 'script_loader_tag', function( $tag, $handle ) {
        if ( ! preg_match( '/^rnw-/', $handle ) ) { return $tag; }
        return str_replace( ' src', ' async defer src', $tag );
    }, 10, 2 );




    return "<div id='root'></div>";
});
