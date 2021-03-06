jQuery(function ($) {
   $( document ).ready(function() {

   // jQuery( document ).on( 'elementor/popup/show', () => {

        'use strict';

        var curentPosition = 0,
            curentTest = null,
            form = $('#envy-quiz'),
            titleQuestion = $('.question-title-content-text'),
            quizBodyBontent = $('#quiz-body-content'),
            submitBtn = $('#btnsubmit'),
            nextBtn = $('#next-question'),
            prevBtn = $('#prev-question'),
            radioBtn = $('.radio');

        var activeProgress = $('.progress-bar-envybox-active'),
            currentQuestion = $('#current-question'),
            totalQuestions = $('#total-questions');

        var numCurrent = 1,
            numTotal = 9;

        var test = new Array(),
            currentQuestionTitle;

        Init();

        function Init() {
            if (curentTest == null) {
                curentTest = form.data('test');
                console.log("curentTest:" + curentTest);
            }
            else {
                return 0;
            }
            getTestLeth();
            getCurrentQuestion();
            showConrtols();
            showFooter();
        }

        function getTestLeth(){
            $.ajax({
                type: "GET",
                url: ajax_object.ajaxurl,
                data: {
                    action: 'get_count_questions',
                    id: curentTest
                },
                success: function (response) {

                    var responseJSON = JSON.parse(response);
                    numTotal = responseJSON[0]['length'];
                }
                });

        }

        function getCurrentQuestion() {

            $.ajax({
                type: "GET",
                url: ajax_object.ajaxurl,
                data: {
                    action: 'get_question',
                    test: curentTest,
                    position: curentPosition
                },
                success: function (response) {
                    var responseJSON = JSON.parse(response);

                    titleQuestion.html(responseJSON['q'][0]['question']);
                    currentQuestionTitle = responseJSON['q'][0]['question'];

                    var countAnsvers = responseJSON['a'].length;
                    quizBodyBontent.empty();

                    let img = '';
                    let style = "style = 'width:100%'";
                    let questionChrk = "style = 'position: absolute; width: 100%; height: 100%;'";
                    let styletext = "margin-left: 35px; padding: 11px; text-align: left;";

                    for (var i = 0; i < countAnsvers; i++) {

                        var ans = responseJSON['a'][i]['answer'];

                        if (responseJSON['a'][i]['idattachimg']) {
                            img = '<img src="' + responseJSON['a'][i]['idattachimg'] + '" />';
                            style = '';
                            questionChrk = '';
                            styletext = "";
                        }




                        if (responseJSON['a'][i]['answer'] == 'scroll') {
                            ans = '<div class="question-el js-resize-width slider-price-wrap" style="background: none;width: 100%;">' +
                                '<input type="number" value="17500000" min="1900000" id="price-output" />' +
                                '<input type="range" min="0" max="50000000" step="50000" value="25000000" class="slider-price" id="slider-price" />' +
                                '<div><div style="float:left">0??</div><div style="float:right">50000000??</div></div>' +
                                '</div>';
                            quizBodyBontent.append(ans);
                            nextBtn.data('next', responseJSON['a'][i]['nextquestion']);
                            test[curentPosition] = [currentQuestionTitle, 0];
                            return 0;
                        } else {

                            if (ans == 'freetext') {
                                ans = '<input type="text" name="g" placeholder="????????????" class="quiz-custom text text-input-other" data-next="' + ans + '" >';
                                nextBtn.data('next', responseJSON['a'][i]['nextquestion']);
                             //   console.log('Next1 '+ responseJSON['a'][i]['nextquestion']);
                            }

                            ans = '<div class="text" style="height: auto;' + styletext + '">' + ans + '</div>';

                            if(responseJSON['q'][0]['type'] == 0) {


                                quizBodyBontent.append(
                                    '<div class="question-el js-resize-width" ' + style + '>' +
                                    '<div class="question-check radio radio-styled" ' + questionChrk + '>' +
                                    '<label><input type="radio" name="name1"  value="' + responseJSON['a'][i]['answer'] + '" data-next="' + responseJSON['a'][i]['nextquestion'] + '">' +
                                    '<span></span>' + img + '</label>' +
                                    '</div>' +
                                    ans +
                                    '</div>');

                            }
                            else {
                                quizBodyBontent.append(
                                    '<div class="question-el js-resize-width" ' + style + '>' +
                                    '<div class="question-check checkbox checkbox-styled" ' + questionChrk + '>' +
                                    '<label><input type="checkbox" name="name1"  value="' + responseJSON['a'][i]['answer'] + '" data-next="' + responseJSON['a'][i]['nextquestion'] + '">' +
                                    '<span></span>' + img + '</label>' +
                                    '</div>' +
                                    ans +
                                    '</div>');

                                    nextBtn.data('next', responseJSON['a'][i]['nextquestion']);
                              //  console.log('Next '+ responseJSON['a'][i]['nextquestion']);
                            }

                        }


                    }

                    //console.log(countansvers);


                }
            });
        }

        function showConrtols() {
            submitBtn.hide();
        }

        function nextQestion(id) {
            if (curentPosition == null || curentPosition == -1) {
                numCurrent++;
                titleQuestion.html('?????????????? ???????? ???????????????? ?????? ?????????? ?? ????????');

                quizBodyBontent.empty();

                quizBodyBontent.append(
                    '<div class="body"><div class="input-container"><label>?????????????? ??????' +
                    '</label><input class="input-agent" name = "agent-name" id ="agent-name"  placeholder="??????"/>' +
                    '</div><br /><div class="input-container">'+
                    '' +
                    '<label >?????????????? ??????????????' +
                    '</label><input class="input-agent" name = "agent-contact" id="agent-contact" placeholder="+7 (111) 111-11-11"/>' +
                    '</div></div>');

                nextBtn.hide();
                prevBtn.hide();

                submitBtn.show();

                console.log(test);

                return;
            } else {
                getCurrentQuestion();
                numCurrent++;
                showFooter();
            }

        }


        $(document).on("change", ".radio", function () {
            if($(this).children().children('input').val()) {
                test[curentPosition] = [ currentQuestionTitle , $(this).children().children('input').val()];
            }

            curentPosition = $(this).children().children('input').data('next');

            nextQestion(0);
        });

        /* SLIDER */
        $(document).on("input", "#slider-price", function () {
            $('#price-output').val($(this).val());

        //    if($(this).val()) {
                test[curentPosition] = [currentQuestionTitle, $(this).val()];
        //    }


        });

        $(document).on("input", "#price-output", function () {
            if ($(this).val() > 0) {
                $('#slider-price').val($(this).val());

               // if($(this).val()){
                    test[curentPosition] =  [  currentQuestionTitle , $(this).val()];
                //}



            }
        });

        /* NEXT / PREV QUESTION */
        nextBtn.on("click", function () {

          var nextq = $(this).data('next');

            if (nextq.length > 0) {

                if ($('#price-output').length > 0)
                {
                   // if($('#price-output').val()){
                        test[curentPosition] = [currentQuestionTitle, $('#price-output').val()];
                        curentPosition = nextq;
                         nextQestion(0);
                    //}

                }

                if($('.checkbox > label > input:checked').length > 0)
                {

                    let str = '';
                    $.each($('.checkbox > label > input:checked'), function( index, value ) {
                        str += $(value).val() +';';
                    });

                    var other = "";

                    if($('.text-input-other').val() != undefined){
                        other = $('.text-input-other').val();
                    }

                    test[curentPosition] = [currentQuestionTitle, str+' ????????????: '+ other];
                    console.log('List'+test);
                    curentPosition = nextq;
                    nextQestion(0);
                }

                $(this).data('next', '');

            }

        });

        prevBtn.on("click", function () {
            $.ajax({
                type: "GET",
                url: ajax_object.ajaxurl,
                data: {
                    action: 'get_prev_id_question',
                    position: curentPosition
                },
                success: function (response) {
                    var responseJSON = JSON.parse(response);
                    curentPosition = parseInt(responseJSON);
                    getCurrentQuestion();
                }
            });
            numCurrent--;
            showFooter();
        });

        /*	FREE TEXT */
        $(document).on("input", '.text-input-other', function (e) {
            //
            //
            //$(this).siblings(".question-check").children().children()[0].setAttribute("checked", "checked");

            $(this).parent().siblings(".question-check").children().children()[0].setAttribute("checked", "checked");

            if($(this).val())
            {
                test[curentPosition] = [currentQuestionTitle, $(this).val()];
            }
            if($(this).data('next') > 0) {
                nextBtn.data('next', $(this).data('next'));
            }

        });

        /* RENDER FOOTER BAR */
        function showFooter() {
            currentQuestion.html(numCurrent);
            totalQuestions.html(numTotal);
            var persent = (numCurrent / numTotal) * 100;
            activeProgress.css("width", persent + "%");
        }

        /* SUBMIT */
        submitBtn.on('click', function () {

            var name, contact;

            $(document).find('#agent-name').each(function () {
                name = $(this);
            });

            $(document).find('#agent-contact').each(function () {
                contact = $(this);
            });

            if(name.val().length < 1 || contact.val().length < 1) {

                if(name.val().length < 1){
                    name.css('border', 'solid 5px red');
                }
                if(contact.val().length < 1){
                    contact.css('border', 'solid 5px red');
                }
            }
            else {

                name = name.val();
                contact = contact.val();
            $.ajax({
                type: "GET",
                url: ajax_object.ajaxurl,
                data: {
                    action: 'save_test',
                    name: name,
                    contact: contact,
                    results: JSON.stringify(test, null, 2)
                },
                success: function (response) {
                    if (response == 1) {
                        $('.dialog-close-button').click();
                        $.alert({
                            title: '?????????????? ???? ?????????????????? ??????????!',
                            content: '?????? ???????????????? ???????????? ?? ????????????????, ???????? ??????????????????????, ???????????????? ?? ???????? ?? ?????????????????? ??????????, ?????????? ???????????????? ???????????? ??????????????????.',
                            theme: 'material',
                        });
                    }
                }
            });
            }

        });


    });
});