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
                    <!-- je definis la cover du book (par l'id) -->
                    <!-- je definis l'url de detail (par l'id) -->
                    <div class="book-card">
                        
                        <div class="book-card__header">
                            <?php $cover_id = 'https://fakerestapi.azurewebsites.net/api/v1/CoverPhotos/books/covers/'.$data['id'] ?>
                            <?php 
                                $json = file_get_contents($cover_id);
                                $json = json_decode($json);
                                $cover_url = $json[0]->url;
                            ?>
                            <?php echo $cover_url ? '<img width="250" height="150" src='.$cover_url.' alt="image auto genérée">' : '<img src="https://fakeimg.pl/250x100/ff0000/">';?>
                        </div>
                        
                        <div class="book-card__body">
                            <h1><?php echo $data['title']?></h1>
                            <p><?php echo $data['description']?></p>
                        </div>

                        <?php $id = 'https://fakerestapi.azurewebsites.net/api/v1/Books/'.$data['id']?>
                        <div class="book-card__footer">
                            <?php $myDate = strtotime($data['publishDate']);?>
                            <?php echo '<a href='.$id.'>Lire</a>'?> 
                            <?php echo '<span>'.date('m/d/Y H:i:s', $myDate).'</span>' ?> 
                        </div>

                        <div class="book-card__option-frame">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M11 17h2v-6h-2Zm1-8q.425 0 .713-.288Q13 8.425 13 8t-.287-.713Q12.425 7 12 7t-.712.287Q11 7.575 11 8t.288.712Q11.575 9 12 9Zm0 13q-2.075 0-3.9-.788-1.825-.787-3.175-2.137-1.35-1.35-2.137-3.175Q2 14.075 2 12t.788-3.9q.787-1.825 2.137-3.175 1.35-1.35 3.175-2.138Q9.925 2 12 2t3.9.787q1.825.788 3.175 2.138 1.35 1.35 2.137 3.175Q22 9.925 22 12t-.788 3.9q-.787 1.825-2.137 3.175-1.35 1.35-3.175 2.137Q14.075 22 12 22Zm0-2q3.35 0 5.675-2.325Q20 15.35 20 12q0-3.35-2.325-5.675Q15.35 4 12 4 8.65 4 6.325 6.325 4 8.65 4 12q0 3.35 2.325 5.675Q8.65 20 12 20Zm0-8Z"/></svg>
                        </div>
                        
                        <div class="book-card__excerpt-frame">
                            <div class="book-card__excerpt-frame__body">
                                <h3>Extrait</h3>
                                <p><?php echo $data['excerpt']?></p>
                                <div>
                                    <button><svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M6.4 19 5 17.6l5.6-5.6L5 6.4 6.4 5l5.6 5.6L17.6 5 19 6.4 13.4 12l5.6 5.6-1.4 1.4-5.6-5.6Z"/></svg></button>
                                </div>
                            </div>
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

