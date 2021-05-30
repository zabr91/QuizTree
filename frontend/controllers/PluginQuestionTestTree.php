<?php

namespace TestQuestionTree;

use Elementor\Plugin;

$widget = new PluginQuestionTestTree();

class PluginQuestionTestTree {
 
  /**
   * Instance
   *
   * @since 1.0.0
   * @access private
   * @static
   *
   * @var Plugin The single instance of the class.
   */
 
  /**
   * Instance
   *
   * Ensures only one instance of the class is loaded or can be loaded.
   *
   * @since 1.2.0
   * @access public
   *
   * @return Plugin An instance of the class.
   */
  public static function instance() {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }
 
    return self::$_instance;
  }
 
  /**
   * widget_scripts
   *
   * Load required plugin core files.
   *
   * @since 1.2.0
   * @access public
   */
  public function widget_scripts() {


     wp_enqueue_script( 'TQT-jconfirm-js',
         TQT_PLUGIN_URL.'frontend/assets/dist/jquery-confirm/jquery-confirm.min.js', ['jquery'], false, true );

      wp_enqueue_script('TQT-js', TQT_PLUGIN_URL.'frontend/assets/js/common.js', ['jquery', 'TQT-jconfirm-js']);


    wp_localize_script( 'TQT-js', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );


     wp_enqueue_style('TQT-css', TQT_PLUGIN_URL.'frontend/assets/css/style.min.css');

      wp_enqueue_style( 'TQT-jconfirm-css',
          TQT_PLUGIN_URL.'frontend/assets/dist/jquery-confirm/jquery-confirm.min.css'  );
  }
 
  /**
   * Include Widgets files
   *
   * Load widgets files
   *
   * @since 1.2.0
   * @access private
   */
  private function include_widgets_files() {
    require_once( TQT_PLUGIN_DIR.'frontend/widgets/QuestionTestTree.php' );
  }
 
  /**
   * Register Widgets
   *
   * Register new Elementor widgets.
   *
   * @since 1.2.0
   * @access public
   */
  public function register_widgets() {
    // Its is now safe to include Widgets files
    $this->include_widgets_files();    

    Plugin::instance()->widgets_manager->register_widget_type( new QuestionTestTree_widget() );
  }

  function register_categories( $elements_manager ) {

  $elements_manager->add_category(
    'question-test-tree',
    [
      'title' => __( 'QuestionTestTree', '' ),
      'icon' => 'fa fa-square-full',
    ]
  );
  }


  /**
   *  Plugin class constructor
   *
   * Register plugin action hooks and filters
   *
   * @since 1.2.0
   * @access public
   */
  public function __construct() {

    //Register category
    add_action( 'elementor/elements/categories_registered',  [ $this, 'register_categories' ]);
 
    // Register widget scripts
    add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );
 
    // Register widgets
    add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

    }
}