<?php 
/*
Plugin Name: Dev Test Task
*/

/**
 * Enqueue scripts
 */
function dev_test_task_plugin_enqueue_scripts() {
    wp_enqueue_script( 'jquery');
    wp_enqueue_script( 'dev-test-task-script', plugin_dir_url( __FILE__ ) . 'assets/js/dev-test-task-script.js', array('jquery'), '1.0', true );
     wp_enqueue_style( 'dev-test-task-style', plugin_dir_url( __FILE__ ) . 'assets/css/dev-test-task-style.css', array(), '1.0' , false );
}
add_action( 'wp_enqueue_scripts', 'dev_test_task_plugin_enqueue_scripts' );

/**
 * Init Custom post types.
 */
add_action( 'init', 'register_post_types' );

function register_post_types(){
    register_post_type('estate-object', array(
        'label'  => null,
        'labels' => array(
            'name'               => 'Объекты недвижимости',
            'singular_name'      => 'Объект недвижимости',
            'add_new'            => 'Добавить',
            'add_new_item'       => 'Добавить объект недвижимости',
            'edit_item'          => 'Редактировать объект недвижимости',
            'new_item'           => 'Новый объект недвижимости',
            'view_item'          => 'Посмотреть объект недвижимости',
            'search_items'       => 'Поиск объектов недвижимости',
            'not_found'          => 'Не найдено',
            'not_found_in_trash' => 'Не найдено в корзине',
            'menu_name'          => 'Объект недвижимости',
        ),
        'description'         => '',
        'public'              => true,
        'show_in_menu'        => null,
        'show_in_rest'        => null,
        'rest_base'           => null,
        'menu_position'       => null,
        'menu_icon'           => 'dashicons-building', 
        'hierarchical'        => false,
        'supports'            => array('title'),
        'taxonomies'          => array('district'),
        'has_archive'         => true,
        'rewrite'             => true,
        'query_var'           => true,
    ) );

    register_taxonomy('district', 'estate-object', array(
    	'labels' => array(
    		'name' 				=> 'Районы',
    		'singular_name' 	=> 'Район',
    		'search_items' 		=> 'Поиск района',
    		'popular_items' 	=> 'Популярные районы',
    		'all_items' 		=> 'Все районы',
    		'edit_item' 		=> 'Редактировать район',
    		'update_item' 		=> 'Обновить район',
    		'add_new_item' 		=> 'Добавить новый район',
    		'view_item'			=> 'Посмотреть район',
    		'not_found' 		=> 'Не найдено',
    	),
    	'hierarchical' 		=> true,
    	'show_ui'       	=> true,
		'query_var'     	=> true,
		'show_admin_column' => true,

    ));
}

// Replaces "meta_key = " to "meta_key LIKE" for needed meta keys
function dev_test_task_plugin_posts_where( $where ) {
    $where = str_replace('meta_key = \'object_apartments_$', 'meta_key LIKE \'object_apartments_%', $where);
    return $where;
}
add_filter('posts_where', 'dev_test_task_plugin_posts_where');

/**
 * Estate Objects form submit
 */
function estate_objects_form_action(){
    $received_data = serialized_array_to_json($_POST['data']);

    $object_name = $received_data['object_name'];
    $object_location = $received_data['object_location'];
    $object_floors_count = $received_data['object_floors-count'];
    $object_build_type = $received_data['object_build-type'];
    $object_environmental_friendliness = $received_data['object_environmental-friendliness'];

    $apartament_square = $received_data['apartament_square'];
    $apartament_rooms_count = $received_data['apartament_rooms-count'];
    $apartament_balcony = $received_data['apartament_balcony'];
    $apartament_bathroom = $received_data['apartament_bathroom'];

    $args = array(
        'post_type' => 'estate-object',
        'posts_per_page' => 5,
        'suppress_filters' => false,
        'meta_query' => array(
            'relation' => 'AND',
        )
    );

    if($object_name){
        array_push($args['meta_query'], array(
            'key' => 'object_name',
            'value' => $object_name,
            'compare' => 'LIKE'
        ));
    }

    if($object_location){
        array_push($args['meta_query'], array(
            'key' => 'object_location',
            'value' => $object_location,
            'compare' => 'LIKE'
        ));
    }

    if($object_floors_count){
        array_push($args['meta_query'], array(
            'key' => 'object_floors-count',
            'value' => $object_floors_count,
            'compare' => '='
        ));
    }

    if($object_build_type){
        array_push($args['meta_query'], array(
            'key' => 'object_build-type',
            'value' => $object_build_type,
            'compare' => '='
        ));
    }

    if($object_environmental_friendliness){
        array_push($args['meta_query'], array(
            'key' => 'object_environmental-friendliness',
            'value' => $object_environmental_friendliness,
            'compare' => '='
        ));
    }

    if($apartament_square){
        array_push($args['meta_query'], array(
            'key' => 'object_apartments_$_square',
            'value' => $apartament_square,
            'type' => 'NUMERIC'
        ));
    }

    if($apartament_rooms_count){
        array_push($args['meta_query'], array(
            'key' => 'object_apartments_$_rooms-count',
            'value' => $apartament_rooms_count,
            'compare' => '='
        ));
    }

    if($apartament_bathroom){
        array_push($args['meta_query'], array(
            'key' => 'object_apartments_$_bathroom',
            'value' => $apartament_bathroom,
            'compare' => '='
        ));
    }

    if($apartament_balcony){
        array_push($args['meta_query'], array(
            'key' => 'object_apartments_$_balcony',
            'value' => $apartament_balcony,
            'compare' => '='
        ));
    }

    $estate_objects = new WP_Query($args);

    echo render_estate_objects_wrapper($estate_objects);

    wp_die();
}

add_action('wp_ajax_estate_objects_form_action', 'estate_objects_form_action');
add_action('wp_ajax_nopriv_estate_objects_form_action', 'estate_objects_form_action');

/**
 * Init shortcode [estate_objects]
 */
function get_estate_objects_shortcode( $atts ) {
    $html = '<div id="estate-objects-container" class="container-fluid">';
    $html .= render_estate_objects_form();
    $html .= '
        <div class="content mt-3 pt-3">
            <div class="loader d-none"></div>
        </div>
    </div>';
    return $html;
}

add_shortcode( 'estate_objects', 'get_estate_objects_shortcode' );

/** 
 * Init Widget
 */
class Estate_Objects_Widget extends WP_Widget {

    function __construct() {
        // Запускаем родительский класс
        parent::__construct(
            '',
            'Виджет объектов недвижимости',
            array('description' => 'Виджет объектов недвижимости')
        );
    }

    function widget( $args, $instance ){
        $title = apply_filters( 'widget_title', $instance['title'] );

        echo $args['before_widget'];

        if( $title )
            echo $args['before_title'] . $title . $args['after_title'];

        echo 'Привет!';

        echo $args['after_widget'];
    }
}

add_action( 'widgets_init', 'dev_test_task_plugin_register_widgets' );
function dev_test_task_plugin_register_widgets() {
    register_widget( 'Estate_Objects_Widget' );
}

// Other functions

/**
 * Renders estate objects form
 *
 * Generates HTML code of the form for
 * "Estate objects" post type
 *
 * @return (string) HTML code of the estate objects form
 */
function render_estate_objects_form(){
    $html = '
    <form id="estate-objects-form" class="card text-white p-3 bg-dark w-100" action="' . admin_url('admin-ajax.php') . '">
        <div class="card-header"><h4 class="text-white">Объекты недвижимости</h4></div>
        <div class="card-body">
            <h5 class="card-title text-white">Параметры объектов недвижимости</h5>
            <div class="row">
                <div class="input-group col-5 mr-2 mb-2">
                    <label for="object-location" class="d-block w-100">Координаты местонахождения</label>
                    <input type="text" name="object_location" class="form-control" id="object-location" placeholder="lat, long">
                </div>
                <div class="input-group col-5 mb-2">
                    <label for="object-floors-count" class="d-block w-100">Количество этажей</label>
                    <select class="custom-select" name="object_floors-count" id="object-floors-count">
                        <option selected value="">Количество</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                    </select>
                </div>
                <div class="input-group col-5 mb-2 mr-2">
                    <label for="object-build-type" class="d-block w-100">Тип строения</label>
                    <select class="custom-select" name="object_build-type" id="object-build-type">
                        <option selected value="">Тип</option>
                        <option value="Панель">Панель</option>
                        <option value="Кирпич">Кирпич</option>
                        <option value="Пеноблок">Пеноблок</option>
                    </select>
                </div>
                <div class="input-group col-5 mb-2">
                    <label for="object-environmental-friendliness" class="d-block w-100">Экологичность</label>
                    <select class="custom-select" name="object_environmental-friendliness" id="object-environmental-friendliness">
                        <option selected value="">Бал</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
            </div>
            <h5 class="card-title text-white mt-2">Параметры помещений</h5>
            <div class="row">
                <div class="input-group col-5 mr-2 mb-2">
                    <label for="apartament-square" class="d-block w-100">Площадь, м²</label>
                    <input type="number" min="0" name="apartament_square" class="form-control" id="apartament-square">
                </div>
                <div class="input-group col-5 mb-2">
                    <label for="apartament-rooms-count" class="d-block w-100">Количество комнат</label>
                    <select class="custom-select" name="apartament_rooms-count" id="apartament-rooms-count">
                        <option selected value="">Количество</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>
                <div class="input-group col-5 mr-2 mb-2">
                    <label for="apartament-balcony" class="d-block w-100">Балкон</label>
                    <select class="custom-select" name="apartament_balcony" id="apartament-balcony">
                        <option selected value="">Наличие</option>
                        <option value="Да">Да</option>
                        <option value="Нет">Нет</option>
                    </select>
                </div>
                <div class="input-group col-5 mb-2">
                    <label for="apartament-bathroom" class="d-block w-100">Санузел</label>
                    <select class="custom-select" name="apartament_bathroom" id="apartament-bathroom">
                        <option selected value="">Наличие</option>
                        <option value="Да">Да</option>
                        <option value="Нет">Нет</option>
                    </select>
                </div>

            </div>

        </div>

        <div class="card-footer">
            <div class="form-inline">
                <input class="form-control mr-sm-2" type="text" name="object_name" placeholder="Имя здания">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Искать</button>
            </div>
        </div>

    </form>
    ';

    return $html;
}

/**
 * Renders estate objects wrapper
 *
 * Generates HTML code for the wrapper of
 * "Estate objects" post type
 *
 * @param (WP_Query) $estate_objects Generated WP_Query object of estate objects
 *
 * @return (string) HTML code of the estate objects wrapper
 */
function render_estate_objects_wrapper($estate_objects){
    $html = '<div id="estate-objects-wrapper" class="w-100">';

    if($estate_objects->have_posts()){
        $html .= '<div class="row">';
        while($estate_objects->have_posts()){
            $estate_objects->the_post();
            $object_name = get_field('object_name');
            $object_floors_count = get_field('object_floors-count');
            $object_build_type = get_field('object_build-type');
            $object_image = get_field('object_image');
            $object_environmental_friendliness = get_field('object_environmental-friendliness');
            $object_apartments_count = count(get_field('object_apartments'));
            $object_districts = get_the_terms(get_the_ID(), 'district');

            $html .= '
            <article class="card col-12 col-sm-6 col-lg-4">
                <img class="card-img-top mt-2" src="' . $object_image . '" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">' . $object_name . '</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Количество этажей - ' . $object_floors_count .'</li>
                        <li class="list-group-item">Тип строения - ' . $object_build_type . '</li>
                        <li class="list-group-item">Экологичность - ' .$object_environmental_friendliness. '</li>
                        <li class="list-group-item">Количество помещений - ' .$object_apartments_count .'</li>';
            if($object_districts){
                $html .= '<li class="list-group-item">Район - ' . $object_districts[0]->name . '</li>';
            }                        
            $html .= '
                    </ul>
                    <a href="' . esc_url( get_permalink() ) .'" class="btn btn-primary mt-2">Подробнее</a>
                </div>
            </article>';
        }
        wp_reset_postdata();
        $html .= '</div>';
    } else {
        $html .= '<h2>Объекты недвижимости не найдены</h2>';
    }

    $html .= '</div>';

    return $html;
}

function serialized_array_to_json($array){
    $json = array();
    
    foreach ($array as $item) {
        $json[$item['name']] = $item['value'];
    }

    return $json;
}

?>
