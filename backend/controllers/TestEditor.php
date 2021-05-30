<?php
/*
 * Admin panel test editor
 */
namespace TestQuestionTree;

$testEditor = new TestEditor();

class TestEditor
{
	public static $currentAnsver;
	public static $currentQestion;
	public static $currentTest;
	
	function __construct()
	{		

		// WP 5.4.2. Cохранение опции экрана per_page. Нужно вызывать до события 'admin_menu'
		add_filter( 'set_screen_option_'.'lisense_table_per_page', function( $status, $option, $value ){
			return (int) $value;
		}, 10, 3 );

		// WP < 5.4.2. сохранение опции экрана per_page. Нужно вызывать рано до события 'admin_menu'
		add_filter( 'set-screen-option', function( $status, $option, $value ){
			return ( $option == 'lisense_table_per_page' ) ? (int) $value : $status;
		}, 10, 3 );

		// создаем страницу в меню, куда выводим таблицу
		add_action( 'admin_menu', function(){//page-slug
			$hook = add_menu_page( 'Qestion Test Ttree', 'Cоздание разветвленных тестов', 'manage_options', 'TQT-tests', 
				[&$this, 'view'], 'dashicons-forms', 100 );

					add_action( "load-$hook", [&$this, 'page_load'] );
		
		} );

		add_action('admin_init', [&$this, 'addControls']);
		add_action( 'admin_enqueue_scripts', [&$this, 'load_wp_media_files'] );
	}

	
	
	function load_wp_media_files( $page )
    {
        if(isset($_GET['action']))
        {
  		    if($_GET['action'] == 'edit-answer')
  		    {
    	        wp_enqueue_media();
    	        wp_enqueue_script( 'admin_script', TQT_PLUGIN_URL . 'backend/assets/js/common.js', array('jquery'), '0.1' );
  		    }
        }
	}

	function page_load(){

		if(isset($_GET['action']))
		{
			if($_GET['action'] == 'edit-qestions')
			{
		 	 require_once TQT_PLUGIN_DIR . 'backend/models/TableQestions.php'; 
			 $GLOBALS['List_Table'] = new TableQestions();
			}
			if($_GET['action'] == 'edit-answer'){

			}
		}
		else {
			require_once TQT_PLUGIN_DIR . 'backend/models/TableTests.php'; 
		    $GLOBALS['List_Table'] = new TableTests();
		}
	}

    /*
    *
    */
	function view() {
		if(isset($_GET['action']))
		{
			if($_GET['action'] == 'edit-qestions')
			{
				require_once TQT_PLUGIN_DIR.'backend/views/viewqestions.php';
			}
            if($_GET['action'] == 'add-test')
            {
                if($_POST){
                    $this->saveTest($_POST);
                }


                require_once TQT_PLUGIN_DIR.'backend/views/viewaddtest.php';
            }
			if($_GET['action'] == 'edit-answer'){

				if( $_GET['deleteans'] ) {
					$this->deleteAnswer($_GET['deleteans']);
				}

				if( $_POST ) {	
					$this->saveAnswer($_POST); 
					//wp_die();
				}
				
				if(isset($_GET['id'])) {

				$db = new BaseCustomData('qtt_answers');
				self::$currentAnsver  = $db->get_by([ 'idquestions' => $_GET['id']] );
				unset($db);

				$db = new BaseCustomData('qtt_questions');
				self::$currentQestion = $db->get_by([ 'idquestions' => $_GET['id'] ]);
				unset($db);

				$db = new BaseCustomData('qtt_questions');
				self::$currentTest = $db->get_by([ 'idtest' => self::$currentQestion[0]->idtest ]);
				unset($db);
				}


				//var_dump(self::$currentAnsver);
 
				require_once TQT_PLUGIN_DIR.'backend/views/viewanswer.php';
			}
		}
		else{		
			require_once TQT_PLUGIN_DIR.'backend/views/viewtests.php';
		}
	}

	private function deleteAnswer($id)
	{
		$answer = new BaseCustomData('qtt_answers');
		$answer->delete(['idanswers' => intval($id) ]);
	}

	private function saveAnswer($data){


		$idTest = intval($_GET['test']);
		$idQuestion = intval($_GET['id']);

		$questionDB = new BaseCustomData('qtt_questions');

        var_dump($data["question"]['type']);
		
		// Question create / upadate
		if( isset( $idQuestion ) & $idQuestion > 0 )
		{
			$questionDB->update([
				'question' => $data["question"]['question'],
				'type' => $data["question"]['type'],
				//'position' => $idQuestion
			], [
				'idquestions' => $idQuestion
			]);
		}
		else {
			//echo "INSERT ". $data["question"]['question'];
			$idQuestion = $questionDB->insert([
				'idtest' => $idTest,
				'question' =>  $data["question"]['question'],
                'type' => $data["question"]['type']
			]);

		//	wp_redirect( '?page=TQT-tests&test='.$idTest.'&action=edit-answer&id='.$idTest, 301 );
			//exit();
		}
        $answersDB = new BaseCustomData('qtt_answers');
		if($idQuestion > 0) {
		// Answer create update

		$idsInDB = $answersDB->get_by(['idquestions' => $idQuestion], '=', NULL, null, 'idanswers', 'ARRAY_N');
		
        
		$idsInDB = array_map([$this, 'arrayCallback'], $idsInDB);

		$q = 0;
		if(isset($data['answer'])) { 
		foreach ($data['answer'] as $key => $value) {			

			if(in_array($key, $idsInDB))
			{
					$answersDB->update([
					'answer' => $value,
					'nextquestion' => $data["nextquestion"][$q],
					//'position' => $key
				],[
					'idanswers' => $key
				]);
			}
			$q++;			
		   }
        }


         //   var_dump($data['newanswer']);

            $q = 0;
            if(isset($data['newanswer'])) {
                foreach ($data['newanswer'] as $key => $value) {


                    $answersDB->insert([
                        'idquestions' => $idQuestion,
                        'answer' => $value,

                    ]);

                    $q++;
                }
            }
		
		}
	}

	private function saveTest($data){

        $db = new BaseCustomData('qtt_tests');
        if(isset($_POST['idtest'])){
         /*   $db->insert([
                'title' => $_POST['title'],
                'length' => $_POST['length']
            ]);*/
        }
        else {
            $db->insert([
                'title' => $_POST['title'],
                'length' => $_POST['length']
            ]);
        }



    }

	function arrayCallback($n)
	{
    	return intval($n[0]);
	}

	private function delete()
	{
	/*	if(isset($_GET['action'])) {
	    if($_GET['action'] == 'deleteprice')
		{
			$price = new BaseCustomData('tc_price');
		   
		    $price->delete(['id' => intval($_GET['id']) ]);

		}}*/
	}

	public function addControls(){
	/*register_setting( 'TransportCalc', 'TransportCalc', 'sanitize_callback' );


	add_settings_section( 'section_id', 'Основные настройки', '', 'TransportCalc' ); 

	add_settings_field('yandex_api', 'Яндекс API', [&$this, 'fill_yandex_api'], 'TransportCalc', 'section_id' );

	add_settings_field('email', 'email менеджера', [&$this, 'fill_email'],       'TransportCalc', 'section_id' );


	add_settings_field('fromemail', 'email с которого будет отправка менеджеру', [&$this, 'fill_fromemail'],       'TransportCalc', 'section_id' );*/
   }

   /*function fill_yandex_api(){
	$val = get_option('TransportCalc');
	$val = $val ? $val['yandex_api'] : null;
		?>
		<input type="text" name="TransportCalc[yandex_api]" value="<?php echo esc_attr( $val ) ?>" />
		<?php
	}

	function fill_email(){
	$val = get_option('TransportCalc');
	$val = isset($val['email']) ? $val['email'] : null;
		?>
		<input type="text" name="TransportCalc[email]" value="<?php echo esc_attr( $val ) ?>" />
		<?php
	}
    
    function fill_fromemail(){
	$val = get_option('TransportCalc');
	$val = isset($val['fromemail']) ? $val['fromemail'] : get_option('admin_email');
		?>
		<input type="text" name="TransportCalc[fromemail]" value="<?php echo esc_attr( $val ) ?>" />
		<?php
	}*/
	/*
	function fill_primer_field2(){
		$val = get_option('TransportCalc');
		$val = $val ? $val['checkbox'] : null;
		?>
		<label><input type="checkbox" name="TransportCalc[checkbox]" value="1" <?php checked( 1, $val ) ?> /> отметить</label>
		<?php
	}*/

	## Очистка данных
	function sanitize_callback( $options ){ 
		// очищаем
		foreach( $options as $name => & $val ){
			if( $name == 'input' )
				$val = strip_tags( $val );

			if( $name == 'checkbox' )
				$val = intval( $val );
		}

		return $options;
	}
}

