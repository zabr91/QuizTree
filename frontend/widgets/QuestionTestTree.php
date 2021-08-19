<?
namespace TestQuestionTree;

use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class QuestionTestTree_widget extends Widget_Base {
	

	public static $slug = TQT_TEXT_DOMAIN;

	//public static $text_domain = '';

	public function get_name() { return self::$slug; }

	public function get_title() { return __('Question Test Tree', self::$slug); }

	public function get_icon() { return 'fas fa-square-full'; }

	public function get_categories() { return [ 'question-test-tree' ]; }

	//public static $html_class_prefix = 'calculate-from-';

	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Settings', self::$slug ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'select_test',
			[
				'label' => __( 'Select test', self::$slug ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => $this->getTests(),

			]);

        $this->add_control(
            'select_icon',
            [
                'label' => __( 'Select test', self::$slug ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],

            ]);

        $this->add_control(
			'title',
			[
				'label' => __( 'Title', self::$slug ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Пройдите опрос и получите подборку самых выгодных предложений всего за 2 минуты', self::$slug ),
			]
		
		);

				
		$this->end_controls_section();		
}
	public function getTests()
	{
		$tests = new BaseCustomData('qtt_tests');
		$items = $tests->get_all(null, "idtest, title");

		$exarr;

		for ($i=0; $i < count($items); $i++) { 

			$exarr[ $items[$i]->idtest ] = strval( $items[$i]->title);  
		
		}

		unset($tests);
		unset($items);

		return $exarr;
	}

	protected function render() {
		require_once TQT_PLUGIN_DIR . 'frontend/views/main.php'; 
	}



}