<?php

use yii\helpers\Url;

$this->title = 'Восстановление пароля';

echo "Вам на почту отправлено подтверждение для смены пароля<br><br>";
echo "<a href=" . Url::to(['site/index']) . " class='user-log__link_gray'>Вернуться на главную</a>";