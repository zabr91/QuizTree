<h1>Тесты</h1>

<a href="?page=TQT-tests&action=add-test" class="button">Добавить новый тест</a>

<div class="wrap">
    <form action="" method="POST">
	<?php if($GLOBALS['List_Table']) 
	{
		$GLOBALS['List_Table']->display();
	} ?>
	</form>
</div>