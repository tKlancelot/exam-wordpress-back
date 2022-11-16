<?php

/**
 * @package MyBooks
 * @version 1.0.0
 */
/*
Plugin Name: MyBooks
Author: Tarik LOUATAH
Description: Un plugin qui recupere les donnees d'une api et les affiche sur la partie front-end de notre site web
*/

require __DIR__.'/Mybooks.php';

function init_mybooks(){
    $mybooks = new Mybooks();
}

init_mybooks();

function enqueue_styles() {
    
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'style',  $plugin_url . "style.css");
    wp_register_style('style',[]);
}


add_action('wp_enqueue_scripts','enqueue_styles');