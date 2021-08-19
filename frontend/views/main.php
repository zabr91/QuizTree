<?php 
$settings = $this->get_settings_for_display();
?>

<body class="envy-quiz-body fix-height" v-html="">
    <div id="envy-quiz" class="envy-quiz quiz-mobile" data-test = "<?= $settings['select_test'] ?>">
        <div class="quiz-body">
            <div class="main">

                <div class="sidebar">
                    <div class="title-offer mhn overflow-hidden">
                        <p>
                      <span class="icon">
                          <?php \Elementor\Icons_Manager::render_icon( $settings['select_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                      </span>
                            <?= strip_tags($settings['title']) ?>
                        </p>
                    </div>
                    <div class="sidebar-content">
                        <div class="user">
                            <img src="<?= TQT_PLUGIN_URL ?>frontend/assets/img/anatoliy.jpeg" alt="">
                            <div class="user-el">
                                <div class="name">Роман</div>
                                <div class="position">Риэлтор</div>
                            </div>
                        </div>

                     <!--   <div class="line"></div> -->
                        <div class="description" id="description"></div>
                    </div>
                </div>


            	<div class="main-content">


						
					<!-- QESTION --->


					<div class= "question">
		
			 			<div class="question-title-content-text" style="position: relative;"> Вопрос
			 			</div>
					    <div class="body">
             			    <div id="quiz-body-content" class="quiz-body-content">'.
             	 	
             			  </div>
           			    </div>








					<!-- END QESTION --->

		
					<div class="footer">
			
						<div class="progress-envybox">
							<div class="text">
								Вопрос <span id="current-question"></span> из <span id="total-questions"></span>
							</div>

							<div class="progress-bar-envybox">
		    					<div class="progress-bar-envybox-active" style="width: 10%;"></div>
							</div>
						</div> 

						<div class="btns">
							<button class="btn btn-back" id ="prev-question">&larr; Назад</button> 

							<input type="submit" value="&#10004; Отправить" class="btn btn-next hide" id="btnsubmit" aria-invalid="false">

							<button class="btn btn-next" id ="next-question">Далее &rarr;</button>
						</div>

					</div>
                    </div>
				</div>
			</div>
		

        

		</div>
	</div>
</body>