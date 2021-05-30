<a href="?page=TQT-tests&action=edit-qestions&id=<?= $_GET['test'] ?>" class="button"><- Haзад </a>

<form method="post">
    <table id='answers'>
        <tr>
            <td colspan="4">
                <p>Вопрос: id:<?= self::$currentQestion[0]->idquestions ?> <input type="text" name="question[question]" value="<?= self::$currentQestion[0]->question ?>"
                          size=80>
                </p>
            </td>
            <td>
                Тип:
                <select name="question[type]">
                    <option value="0" <?=  self::$currentQestion[0]->type == 0 ? 'selected' : "" ?>>Radio</option>
                    <option value="1" <?=  self::$currentQestion[0]->type == 1 ? 'selected' : "" ?>>Checkbox</option>
                </select>
            </td>
        </tr>

        <tr>
            <td>Действия</td>
            <td>Картинка</td>
            <td>Ответ</td>
            <td>Перейти на вопрос</td>
            <td>Ccылка</td>
        </tr>


        <?php for ($i = 0; $i < count(self::$currentAnsver); $i++) { ?>
            <tr>
                <td>
                    <a href="?page=<?= $_GET['page'] ?>&action=<?= $_GET['action'] ?>&id=<?= $_GET['id'] ?>&deleteans=<?= self::$currentAnsver[$i]->idanswers ?>">Удалить</a>
                </td>
                <td>
                    <?php if (self::$currentAnsver[$i]->idattachimg > 0 ) {
                            echo wp_get_attachment_image(self::$currentAnsver[$i]->idattachimg, 'medium', false, array('id' => 'myprefix-preview-image'));
                        } ?>
                    <input type='button' class="button-primary media_manager" value="Выбрать картинку для ответа"
                           class="media_manager" data-id="<?= self::$currentAnsver[$i]->idanswers ?>"/>
                </td>
                <td>
                    <input type="text" name="answer[<?= self::$currentAnsver[$i]->idanswers ?>]"
                           value="<?= self::$currentAnsver[$i]->answer ?>">
                </td>
                <td>

                    <select name="nextquestion[]">
                        <option value="-1">Нету</option>
                        <?php for ($j = 0; $j < count(self::$currentTest); $j++) { ?>

                            <option value="<?= self::$currentTest[$j]->idquestions ?>"
                                <?= self::$currentAnsver[$i]->nextquestion == self::$currentTest[$j]->idquestions ? "selected" : "" ?> >
                                <?= self::$currentTest[$j]->question ?> : <?= self::$currentTest[$j]->grup ?> </option>
                        <?php } ?>
                    </select>

                </td>

                <td>
                    <a href="?page=TQT-tests&action=edit-answer&id=<?= self::$currentAnsver[$i]->nextquestion ?>">
                        >>Перейти на следующий вопрос</a>
                </td>
            </tr>
        <?php } ?>


    </table>

    <a href="#" id="add-answer" class="button">+ Добавить пункт</a>

    <input type="submit" value="Сохранить" class="button">

</form>
<p><b>freetext</b> - свободный текст, свой вариант</p>
<p><b>scroll</b> - ползунок</p>

<?php




 ?>
<input type="hidden" name="myprefix_image_id" id="myprefix_image_id" value="<?php echo esc_attr($image_id); ?>"
       class="regular-text"/>

<script type="text/javascript">
    jQuery(function ($) {

        var addAswer = $('#add-answer');

        addAswer.on("click", addAswer, function () {
            $('#answers tr:last').after('<tr>' +
                '<td></td><td></td>' +
                '<td><input type="text" name="newanswer[]" value=""></td>' +
                '<td>' +
                '<select name="nextquestion[]">' +
                '<option value="0">Нету</option>' +
                 <?php for ($j = 0; $j < count(self::$currentTest); $j++) {?>
                '<option value = "<?= self::$currentTest[$j]->idquestions ?>" > '+
                 '<?= self::$currentTest[$j]->question ?> : <?= self::$currentTest[$j]->grup ?>' +
                '</option>' +
                <?php } ?>
               '</select>	</td><td></td>' +
                '</tr>'
        )
        });

    });

</script>
