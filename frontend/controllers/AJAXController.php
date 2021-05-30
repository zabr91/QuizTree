<?php
namespace TestQuestionTree;

$ajax = new AJAXController();

class AJAXController
{
	
	function __construct()
	{
		    add_action( 'wp_ajax_get_question',        [ $this, 'ajax_get_question'] );    
            add_action( 'wp_ajax_nopriv_get_question', [ $this, 'ajax_get_question'] );

            add_action( 'wp_ajax_get_prev_id_question',        [ $this, 'ajax_get_prev_id_question'] );    
            add_action( 'wp_ajax_nopriv_get_prev_id_question', [ $this, 'ajax_get_prev_id_question'] );

            add_action( 'wp_ajax_save_test',        [ $this, 'ajax_save_test'] );    
            add_action( 'wp_ajax_nopriv_save_test', [ $this, 'ajax_save_test'] );

            add_action( 'wp_ajax_get_count_questions',        [ $this, 'ajax_get_count_questions'] );
            add_action( 'wp_ajax_nopriv_get_count_questions', [ $this, 'ajax_get_count_questions'] );
	}

	/**
	* @return json
	*/

	public function ajax_get_question(){

		if(!$_REQUEST['test']) return false;

		$idtest = intval($_REQUEST['test']);
		$position = isset($_REQUEST['position']) ? intval($_REQUEST['position']) : 0;
		
		$db = new BaseCustomData('qtt_questions'); ////   	   
		$question = $db->get_by([ 'idtest' => $idtest, 'position' => $position ]);
		unset($db);

		$db = new BaseCustomData('qtt_answers');
		$asnwers = $db->get_by(['idquestions' => $question[0]->idquestions]);

		$images = [];

		foreach ($asnwers as $asnwer){
            $asnwer->idattachimg = wp_get_attachment_image_url( $asnwer->idattachimg );
        }

		$exdata = [
			'q' => $question,
			'a' => $asnwers
		];

		echo json_encode($exdata);
		wp_die();
  	}

  	public function ajax_get_prev_id_question(){
  		$idCurrent = $_REQUEST['position'];
  		$db = new BaseCustomData('qtt_answers');
		$question = $db->get_by(['nextquestion' => $idCurrent], '=', NULL, null, $colums = 'idquestions', 'ARRAY_N');

		//var_dump($question[0][0]);
	    echo json_encode($question[0][0]);
		wp_die();
  	}

  	public function ajax_save_test(){
  		
	  	$formSubject = "Новое сообщение с сайта";  
	
	  	$name = strip_tags($_REQUEST['name']);
	  	$contact =  strip_tags($_REQUEST['contact']);
	  	$results = $_REQUEST['results'];

	  	$results = preg_replace("/[\r\n]+/", " ", $results);       
        $results = stripslashes(trim($results,'"'));
        $results = json_decode($results, true);

        $dataTimeNow = date("Y-m-d H:i:s");

        $formTo = get_option('admin_email');
        $formSubject = "Новое сообщение с сайта ". get_site_url();

	  	ob_start();
			include(TQT_PLUGIN_DIR.'frontend/assets/email/email-template.php');
			$emailContent = ob_get_contents();
		ob_end_clean();
		
		$headers = array('Content-Type: text/html; charset=UTF-8',
		'From: Romanetagi <admin@romanetagi.ru>' . "\r\n");	

    
	    if(wp_mail( $formTo, $formSubject, $emailContent,$headers )){
	     // echo "send mail";
	    } 

	   // echo $emailContent;
	    echo 1;
	    wp_die();
	  }

	  /*
	   * Get count questions in test
	   *
	   * @return int
	   */

	  public function ajax_get_count_questions(){

	    $currentTestId = strip_tags($_REQUEST['id']);

          $db = new BaseCustomData('qtt_tests');

          $json = json_encode(
              $db->get_by(['idtest' => $currentTestId], '=', NULL,  null,  'length' )
          );

          unset($db);

          echo $json;

          wp_die();

      }
}