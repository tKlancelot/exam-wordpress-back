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

function enqueue_styles() {
    
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'style',  $plugin_url . "style.css");
    wp_register_style('style',[]);
}

function test_function(){
    echo 'test function';
}

function register_routes() {
	register_rest_route(
		'https://fakerestapi.azurewebsites.net/api/v1/Books',
		'/(?P<id>\d+)',
		[
			'methods'             => 'GET',
			'callback'            => 'test_function'
		]
	);
}
add_action( 'rest_api_init', 'register_routes' );

add_action('wp_enqueue_scripts','enqueue_styles');

add_action('wp_footer',"call_api");




function call_api(){

    // j'initialise ma ressource     
    $curl = curl_init('https://fakerestapi.azurewebsites.net/api/v1/Books');

    // je definis les options de curl(resolution de l'url)
    curl_setopt_array($curl,[
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 1500
    ]);

    // j'execute ma requete 
    $datas = curl_exec($curl);

    if($datas == false){
        // si data est false, on a rencontré une erreur 
        var_dump(curl_error($curl));
    } else {
        // je verifie le code de la requete 
        if(curl_getinfo($curl,CURLINFO_HTTP_CODE) === 200){
            $datas = json_decode($datas,true);
            // pour chaque book dans la limite de 10 books 

            // je crée une grille en html dans un container 
            ?>
            <div class="plugin-container">
                <div class="grid">
            <?php
            foreach(array_slice($datas, 0, 10) as $data){
                ?>
                <!-- je crée une book-card -->
                <div class="book-card">
                    <h1><?php echo $data['title']?></h1>
                    <p><?php echo $data['description']?></p>

                    <!-- je definis la cover du book (par l'id) -->
                    <?php $cover_id = 'https://fakerestapi.azurewebsites.net/api/v1/CoverPhotos/'.$data['id'] ?>
                    <?php 
                        $json = file_get_contents($cover_id);
                        $json = json_decode($json);
                        $cover_url = $json->url;
                    ?>
                    <img width="250" height="150" src="<?= $cover_url;?>" alt="">

                    <!-- je definis l'url de detail (par l'id) -->
                    <?php $id = 'https://fakerestapi.azurewebsites.net/api/v1/Books/'.$data['id']?>
                    <?php echo '<a href='.$id.'>link</a>';

                    ?>
                    
                </div>
                <?
            }
            ?>
                </div>
            </div>
            <?php
        }

    }

    // je cloture ma requete et libere la memoire associee
    curl_close($curl);

}


add_action( 'admin_menu', 'example_menu_items' );

function example_menu_items() {
    // Create a top-level menu item.
    $hookname = add_menu_page(
        __( 'Mybooks Settings Page', 'example' ),  // Page title
        __( 'Mybooks Settings Page', 'example' ),  // Menu title
        'manage_options',                     // Capabilities
        'example_settings_page',              // Slug
        'example_settings_page_markup',       // Display callback
        'dashicons-book',                   // Icon
        66                                    // Priority/position. Just after 'Plugins'
    );
}

/**
 * Markup callback for the settings page
 */
function example_settings_page_markup(){
    ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        </div>
    <?php
}

?>