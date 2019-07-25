<?php

use yii\helpers\Url;

$this->title = 'Готово!';
echo 'Пароль успешно изменен<br><br>';
echo "<a href=" . Url::to(['site/index']) . ">Перейти к форме ввода логина</a>";