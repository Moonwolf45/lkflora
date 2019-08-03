<?php

use app\models\shops\Shops;
use yii\helpers\Html;

/** @var TYPE_NAME $id_modal_version */

?>

<div class="jsx-modal" data-jsx-modal-id="version-change_<?=$id_modal_version; ?>">
    <div class="jsx-modal__block jsx-modal-popup jsx-modal-popup_version-change">
        <div class="close close-add-store jsx-modal__close"></div>
        <div class="version-change">
            <h3 class="popup__title">
                Отправить заявку на изменении версии?
            </h3>
            <div class="version-change__wrapp">
                <div class="version-change__block">
                    <div class="version-change__sub-title"><?=Shops::getVersion($version); ?></div>
                    <div class="version-change__pre-price">
                        <div class="version-change__icon s-di-vertical-m">
                            <?=Html::img('@web/images/icon/icon-version-change.svg'); ?>
                        </div>
                        <p class="version-change__pre-price-text s-di-vertical-m">15 500 руб</p>
                    </div>
                    <div class="version-change__new">
                        <div class="version-name version-name_version-change">
                            <h3 class="version-name__title version-name__title_version-change">Basic</h3>
                            <p class="version-name__price">22 500 руб</p>
                        </div>
                        <div class="version-change__merits">
                            <p class="version-change__merits-text">
                                Рабочее место флориста и администратора с возможностью удаленного подключения
                            </p>
                            <p class="version-change__merits-text">
                                Дисконтная и бонусная система
                            </p>
                            <p class="version-change__merits-text">
                                Отчеты
                            </p>
                        </div>
                    </div>
                </div>
                <button class="button button_width-200px appeal__button">Отправить</button>
            </div>
        </div>
    </div>
</div>
