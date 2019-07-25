<?php

use yii\helpers\Url;

$this->title = 'Ошибка';

echo "Произошла ошибка - вы пытаетесь восстановить пароль по устаревшему токену<br><br>";
echo "<a href=" . Url::to(['site/index']) . ">Вернуться на главную</a>";