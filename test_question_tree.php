<?php
/**
 * Plugin Name: Elementor test question  tree
 * Description: A simple Elementor
 * Version:     1.1.0
 * Author:      Ivan Zabroda
 * Author URI:  https://zabr91.github.io/
 * Text Domain: elementor-test-question-tree
 */
namespace TestQuestionTree;


if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

define( 'TQT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TQT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'TQT_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'TQT_FILE',  __FILE__  );
define( 'TQT_TEXT_DOMAIN',  'elementor-question-test-tree'  );


include_once TQT_PLUGIN_DIR.'base/BaseCustomData.php';

include_once TQT_PLUGIN_DIR.'base/InstallUnstall.php';

include_once TQT_PLUGIN_DIR.'backend/controllers/TestEditor.php';

include_once TQT_PLUGIN_DIR.'backend/controllers/AJAXBackendController.php';

include_once TQT_PLUGIN_DIR.'frontend/controllers/PluginQuestionTestTree.php';

include_once TQT_PLUGIN_DIR.'frontend/controllers/AJAXController.php';