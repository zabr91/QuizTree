<h1>Вопросы</h1>

<a href="?page=TQT-tests&test=<?= $_GET['id'] ?>&action=edit-answer" class="button">Добавить новый вопрос</a>

<div class="wrap">
    <form action="" method="POST">
	<?php if($GLOBALS['List_Table']) 
	{
		$GLOBALS['List_Table']->display();
	} ?>
	</form>
</div>