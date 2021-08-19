<?php


namespace TestQuestionTree;

$backendAjax = new AJAXBackendController();


class AJAXBackendController
{
    function __construct()
    {
        add_action( 'wp_ajax_save_answer_img',        [ $this, 'ajax_save_answer_img'] );
        add_action( 'wp_ajax_nopriv_save_answer_img', [ $this, 'ajax_save_answer_img'] );
    }

    public function ajax_save_answer_img(){

        $idanswer =    $_REQUEST['idanswer']['id'];
        $idattachimg = $_REQUEST['idattachimg'];

        $data = [
          'idattachimg' => $idattachimg
        ];

        $dbAnswers = new BaseCustomData('qtt_answers');
        $result = $dbAnswers->update($data, ['idanswers' => $idanswer]);

        echo $result;

        wp_die();
    }


}