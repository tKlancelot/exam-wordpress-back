<?php


class Mybooks{

    // j'initialise ma ressource    
    const API_URL = 'https://fakerestapi.azurewebsites.net/api/v1/Books';

    public function __construct()
    {   
        add_action('wp_footer',[self::class,'getBooks']);
        add_action( 'admin_menu', [self::class,'example_menu_items']);
    }

    public function getBooks(){
        $curl = curl_init(self::API_URL);
        // je definis les options de curl(resolution de l'url)
        curl_setopt_array($curl,[
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 100
        ]);
        // j'execute ma requete 
        $datas = curl_exec($curl);
        // si data est false, on a rencontré une erreur 
        // je verifie le code de la requete 
        if($datas == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200){
            return null; 
        }

        $datas = json_decode($datas, true);
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
                        
                        <div class="book-card__header">
                            <!-- je definis la cover du book (par l'id) -->
                            <?php $cover_id = 'https://fakerestapi.azurewebsites.net/api/v1/CoverPhotos/books/covers/'.$data['id'] ?>
                            <?php 
                                $json = file_get_contents($cover_id);
                                $json = json_decode($json);
                                $cover_url = $json[0]->url;
                            ?>
                            <?php echo $cover_url ? '<img width="250" height="150" src='.$cover_url.' alt="">' : '<img src="https://fakeimg.pl/250x100/ff0000/">';?>
                        </div>
                        
                        <div class="book-card__body">
                            <h1><?php echo $data['title']?></h1>
                            <p><?php echo $data['description']?></p>
                        </div>

                        <!-- je definis l'url de detail (par l'id) -->
                        <?php $id = 'https://fakerestapi.azurewebsites.net/api/v1/Books/'.$data['id']?>
                        <div class="book-card__footer">
                            <?php $myDate = strtotime($data['publishDate']);?>
                            <?php echo '<a href='.$id.'>link</a>'?> 
                            <?php echo '<span>'.date('m/d/Y H:i:s', $myDate).'</span>' ?> 
                        </div>
                    </div>
                    <?
                }
                ?>
                </div>
            </div>
        <?php
        // je cloture ma requete et libere la memoire associee
        curl_close($curl);
    }

    public function example_menu_items() {
        // Create a top-level menu item.
        $hookname = add_menu_page(
            __( 'Mybooks Settings Page', 'example' ),  // Page title
            __( 'Mybooks Settings Page', 'example' ),  // Menu title
            'manage_options',                     // Capabilities
            'example_settings_page',              // Slug
            'settings_page_markup',               // callback
            'dashicons-book',                     // Icon
            66                                    // Priorities
        );
        function settings_page_markup(){
            ?>
                <div class="wrap">
                    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
                </div>
            <?php
        }

    }


}

?>

