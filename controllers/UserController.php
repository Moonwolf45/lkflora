<?php

namespace app\controllers;

use app\models\addition\Addition;
use app\models\payments\NewPaid;
use app\models\payments\Payments;
use app\models\service\Service;
use app\models\shops\Shops;
use app\models\ShopsAddition;
use app\models\tariff\Tariff;
use app\models\tickets\Tickets;
use app\models\tickets\TicketsFiles;
use app\models\tickets\TicketsText;
use app\models\traits\UploadFilesTrait;
use app\models\Transaction;
use Yii;
use yii\filters\AccessControl;
use yii\httpclient\Client;
use yii\web\Controller;
use app\models\AuthForm;

use app\models\db\User;
use app\models\db\UserSettings;
use app\models\form\UserProfileForm;
use app\models\form\UserSettingsForm;
use app\models\form\UploadAvatarForm;
use yii\web\UploadedFile;

class UserController extends Controller {
    use UploadFilesTrait;

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['index', 'registration', 'auth'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                    [
                        'actions' => ['auth', 'registration'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Главная страница пользователя
     *
     * @return string
     */
    public function actionIndex() {
        $shops = Shops::find()->joinWith('tariff')->joinWith('additions')
            ->where(['user_id' => Yii::$app->user->id])->asArray()->all();

        $tariffs = Tariff::find()->asArray()->all();
        $additions = Addition::find()->asArray()->all();

        $date_month = date("Y-m-d", mktime(0, 0, 0, date("m") + 1, 31, date("Y")));
        $monthly_payment = Service::find()->where(['user_id' => Yii::$app->user->id, 'completed' => Service::COMPLETED_FALSE])
            ->andWhere(['<=', 'writeoff_date', $date_month])->asArray()->all();

        $invoice = Payments::find()->where(['user_id' => Yii::$app->user->id, 'type' => Payments::TYPE_REFILL,
            'status' => Payments::STATUS_PAID])->andWhere(['!=', 'invoice_number', ''])->orderBy(['id' => SORT_DESC])
            ->limit(3)->asArray()->all();

        $tickets = Tickets::find()->where(['user_id' => Yii::$app->user->id, 'status' => Tickets::STATUS_OPEN_TICKET])
            ->with('lastTicket')->limit(3)->asArray()->all();

        $modelShop = new Shops();
        $newTicket = new Tickets();

        if ($modelShop->load(Yii::$app->request->post()) && $modelShop->save()) {
            Service::saveTariff($modelShop->tariff_id, $modelShop->id, Yii::$app->user->id);
            foreach ($modelShop->addition as $addition_one) {
                $shopAddition = new ShopsAddition();
                $shopAddition->shop_id = $modelShop->id;
                $shopAddition->addition_id = $addition_one;
                $shopAddition->quantity = 1;
                $shopAddition->save();

                Service::saveAddition($addition_one, $modelShop->id, 1, Yii::$app->user->id);
            }

            return $this->refresh();
        }

        if ($newTicket->load(Yii::$app->request->post())) {
            $newTicket->status = Tickets::STATUS_OPEN_TICKET;
            $newTicket->new_text = false;

            $newTicket->ticketFiles = UploadedFile::getInstances($newTicket, 'ticketFiles');
            if ($newTicket->ticketFiles) {
                $this->uploadGallery($newTicket, 'ticketFiles','tickets');
            }
            $newTicket->save(false);

            $newTextTicket = new TicketsText();
            $newTextTicket->ticket_id = $newTicket->id;
            $newTextTicket->date_time = date('Y-m-d H:i:s');
            $newTextTicket->text = $newTicket->tickets_text;
            $newTextTicket->user_type = TicketsText::TYPE_USER_NORMAL;
            $newTextTicket->save(false);

            $newTextFiles = new TicketsFiles();
            foreach ($newTicket->ticketFiles as $ticketFile) {
                $newTextFiles->ticket_id = $newTicket->id;
                $newTextFiles->ticket_text_id = $newTextTicket->id;
                $newTextFiles->file = 'upload/tickets/' . $ticketFile;
                $newTextFiles->save(false);
            }

            return $this->refresh();
        }

        return $this->render('index', compact('shops', 'modelShop', 'tariffs',
            'additions', 'invoice', 'newTicket', 'tickets', 'monthly_payment'));
    }

    /**
     *
     * Просто action для обновления тарифа магазина
     *
     * @return \yii\web\Response
     */
    public function actionUpdateShop() {
        $updateShop = Yii::$app->request->post();

        $shop = Shops::findOne($updateShop['Shops']['id']);
        $oldTariff_id = $shop->tariff_id;
        $shop->tariff_id = $updateShop['Shops']['tariff_id'];
        $shop->save(false);

        Service::saveTariff($updateShop['Shops']['tariff_id'], $updateShop['Shops']['id'], Yii::$app->user->id, $oldTariff_id, true);

        return $this->redirect(['/user/index']);
    }

    /**
     * Просто action для изменения услуг магазина
     *
     * @return \yii\web\Response
     */
    public function actionShopEditService() {
        $shopEditService = Yii::$app->request->post();

        $delete_id = [];
        $additions = ShopsAddition::find()->where(['shop_id' => $shopEditService['Shops']['id']])->asArray()->all();
        foreach ($additions as $addition) {
            $delete_id[] = $addition['addition_id'];
        }
        Service::updateAdditionFalse(Yii::$app->user->id, $shopEditService['Shops']['id'], $delete_id);
        ShopsAddition::deleteAll(['shop_id' => $shopEditService['Shops']['id']]);

        foreach ($shopEditService['Shops']['addition'] as $key => $service) {
            if ($service != 0) {
                $shopAddition = new ShopsAddition();
                $shopAddition->shop_id = $shopEditService['Shops']['id'];
                $shopAddition->addition_id = $key;
                $shopAddition->quantity = $shopEditService['Shops']['quantityArr'][$key];
                $shopAddition->save();

                Service::updateAddition($key, $shopEditService['Shops']['id'], $shopEditService['Shops']['quantityArr'][$key], Yii::$app->user->id);
            }
        }

        return $this->redirect(['/user/index']);
    }

    /**
     * Страница детализации баланса
     *
     * @param $d
     * @param $i
     *
     * @return string
     */
    public function actionPayment($d, $i) {
        if ($d == '') {
            $d = 1;
        }

        if ($i == '') {
            $i = 1;
        }

        $modelPaid = new NewPaid();
        $payments = Payments::find()->where(['user_id' => Yii::$app->user->id, 'type' => Payments::TYPE_WRITEOFF,
            'status' => Payments::STATUS_PAID])->with('shop')->with('tariff')->with('addition')
            ->orderBy(['id' => SORT_DESC])->asArray()->all();

        $deposit = Payments::find()->where(['user_id' => Yii::$app->user->id, 'type' => Payments::TYPE_REFILL,
            'status' => Payments::STATUS_PAID])->orderBy(['id' => SORT_DESC])->limit(3 * $d)->asArray()->all();

        $invoice = Payments::find()->where(['user_id' => Yii::$app->user->id, 'type' => Payments::TYPE_REFILL])
            ->andWhere(['!=', 'invoice_number', ''])->orderBy(['id' => SORT_DESC])->limit(3 * $i)->asArray()->all();

        $maxPaymentId = Payments::getMaxId();

        return $this->render('payment', compact('d', 'i', 'modelPaid', 'payments', 'deposit', 'invoice',
            'maxPaymentId'));
    }

    /**
     * Выставление счета
     *
     * @return string
     */
    public function actionSavePdf() {
        $sumToPdf = Yii::$app->request->post();
        $maxPaymentNumber = Payments::getMaxNumberSchet();
        $date = date('d.m.Y');
        $number = 'E' . ($maxPaymentNumber['invoice_number'] + 1);

        $schetPayment = new Payments();
        $schetPayment->user_id = Yii::$app->user->id;
        $schetPayment->shop_id = 0;
        $schetPayment->type_service = 0;
        $schetPayment->service_id = 0;
        $schetPayment->type = Payments::TYPE_REFILL;
        $schetPayment->way = Payments::WAY_SCHET;
        $schetPayment->date = date('Y-m-d');
        $schetPayment->invoice_number = $maxPaymentNumber['invoice_number'] + 1;
        $schetPayment->invoice_date = date('Y-m-d');
        $schetPayment->amount = $sumToPdf['NewPaid']['amount'];
        $schetPayment->status = Payments::STATUS_EXPOSED;
        $schetPayment->save(false);

        $pdfFile = Yii::$app->pdf;
        $mpdf = $pdfFile->api;
        $mpdf->SetHeader('Счёт №' . $number . ' от ' . $date);
        $mpdf->SetTitle('Счёт №' . $number . ' от ' . $date);

        $user = User::find()->where(['id' => Yii::$app->user->id])->asArray()->limit(1)->one();
        $userSetting = UserSettings::find()->where(['user_id' => Yii::$app->user->id])->asArray()->limit(1)->one();
        $content = $this->renderPartial('_schetPDF', ['number' => $number, 'date' => $date, 'user' => $user,
            'userSetting' => $userSetting, 'amount' => $sumToPdf['NewPaid']['amount']]);
        $mpdf->WriteHtml($content);
        $filename = 'Счёт №' . $number . ' от ' . $date . '.pdf';
        return $mpdf->Output($filename, 'D');
    }

    /**
     * Оплата с карты
     *
     * @param $id
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDownloadAct($id) {
        if ($id != '') {
            $model = Payments::find()->where(['id' => $id])->asArray()->limit(1)->one();

           if (!empty($model)) {
               $user = User::find()->where(['id' => Yii::$app->user->id])->asArray()->limit(1)->one();
               $userSetting = UserSettings::find()->where(['user_id' => Yii::$app->user->id])->asArray()->limit(1)
                   ->one();

               $pdf = Yii::$app->pdf;
               $mpdf = $pdf->api;
               $content = $this->renderPartial('_actPDF', ['model' => $model, 'user' => $user,
                   'userSetting' => $userSetting]);
               $mpdf->WriteHtml($content);

               $number = 'E' . $model['invoice_number'];
               $date = Yii::$app->formatter->asDate($model['date']);
               $mpdf->SetHeader('Акт №' . $number . ' от ' . $date);
               $mpdf->SetTitle('Акт №' . $number . ' от ' . $date);
               $filename = 'Акт №' . $number . ' от ' . $date . '.pdf';
               return $mpdf->Output($filename, 'D');
           }
        }

        return false;
    }

    /**
     * Псевдо страница успешной оплаты
     *
     * @param $transaction_id
     *
     * @return \yii\web\Response
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSuccessPayment($transaction_id) {
        if ($transaction_id != '') {
            $time_now = time();
            $salt = Yii::$app->security->generateRandomString(32);

            $payments = new Payments();
            $signature = $payments->getSignature(['transaction_id' => $transaction_id, 'unix_timestamp' => $time_now,
                'merchant' => Yii::$app->params['idSite'], 'salt' => $salt]);

            $client = new Client(['requestConfig' => ['format' => Client::FORMAT_URLENCODED],
                'responseConfig' => ['format' => Client::FORMAT_JSON]]);

            $response = $client->createRequest()->setMethod('GET')
                ->setUrl('https://pay.modulbank.ru/api/v1/transaction/')
                ->setData(['transaction_id' => $transaction_id, 'merchant' => Yii::$app->params['idSite'],
                    'unix_timestamp' => $time_now, 'salt' => $salt,
                    'signature' => $signature])->send();
            if ($response->isOk) {
                $resp_array = json_decode($response->content);

                if ($resp_array->status == 'ok') {
                    $user = User::findByEmail($resp_array->transaction->client_email);
                    $payments->user_id = $user->id;
                    $payments->shop_id = 0;
                    $payments->type_service = 0;
                    $payments->service_id = 0;
                    $payments->type = Payments::TYPE_REFILL;
                    $payments->way = Payments::WAY_CARD;
                    $payments->date = date('Y-m-d');
                    $payments->invoice_date = date('Y-m-d');
                    $payments->amount = $resp_array->transaction->amount;
                    $payments->description = $resp_array->transaction->description;

                    if ($resp_array->transaction->state == 'COMPLETE') {
                        $user->balance += $resp_array->transaction->amount;
                        $user->save(false);

                        Yii::$app->session->setFlash('success', 'Оплата на ' . Yii::$app->formatter
                                ->asDecimal($resp_array->transaction->amount, 2) . 'руб прошла успешно.');

                        $payments->status = Payments::STATUS_PAID;
                    } elseif ($resp_array->transaction->state == 'PROCESSING' || $resp_array->transaction->state == 'WAITING_FOR_3DS') {
                        Yii::$app->session->setFlash('success', 'Оплата находится в процессе, как только статус изменится деньги тут же постуят на ваш счет');

                        $payments->status = Payments::STATUS_WAITING;
                    }  elseif ($resp_array->transaction->state == 'FAILED') {
                        Yii::$app->session->setFlash('error', 'Извините, но во время оплаты произошла какая-то неизвестная ошибка');

                        $payments->status = Payments::STATUS_CANCEL;
                    }
                    $payments->save(false);

                    if ($resp_array->transaction->state == 'PROCESSING' || $resp_array->transaction->state == 'WAITING_FOR_3DS') {
                        $transaction = new Transaction();
                        $transaction->user_id = $user->id;
                        $transaction->transaction_id = $transaction_id;
                        $transaction->payment_id = $payments->id;
                        $transaction->status = Transaction::STATUS_REPEAT;
                        $transaction->save(false);
                    }

                    return $this->redirect(['/user/payment', 'd' => 1, 'i' => 1]);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Во время оплаты произошла неизвестная ошибка.');
                return $this->redirect(['/user/payment', 'd' => 1, 'i' => 1]);
            }
        }

        Yii::$app->session->setFlash('error', 'Во время оплаты произошла неизвестная ошибка.');
        return $this->redirect(['/user/payment', 'd' => 1, 'i' => 1]);
    }

    /**
     * Псевдо страница плохой оплаты
     *
     * @param $transaction_id
     *
     * @return \yii\web\Response
     */
    public function actionFalsePayment($transaction_id) {
        if ($transaction_id != '') {
            Yii::$app->session->setFlash('error', 'Извините, но во время оплаты произошла какая-то неизвестная ошибка');
        }

        Yii::$app->session->setFlash('error', 'Во время оплаты произошла неизвестная ошибка.');
        return $this->redirect(['/user/payment', 'd' => 1, 'i' => 1]);
    }

    /**
     * Страница тех. потдержки
     *
     * @param string $id
     *
     * @return string
     */
    public function actionTickets($id = '') {
        $tickets = Tickets::find()->where(['user_id' => Yii::$app->user->id, 'status' => Tickets::STATUS_OPEN_TICKET])
            ->with('lastTicket')->asArray()->all();

        $newTicket = new Tickets();
        if ($newTicket->load(Yii::$app->request->post())) {
            $newTicket->status = Tickets::STATUS_OPEN_TICKET;
            $newTicket->new_text = false;

            $newTicket->ticketFiles = UploadedFile::getInstances($newTicket, 'ticketFiles');
            if ($newTicket->ticketFiles) {
                $this->uploadGallery($newTicket, 'ticketFiles','tickets');
            }
            $newTicket->save(false);

            $newTextTicket = new TicketsText();
            $newTextTicket->ticket_id = $newTicket->id;
            $newTextTicket->date_time = date('Y-m-d H:i:s');
            $newTextTicket->text = $newTicket->tickets_text;
            $newTextTicket->user_type = TicketsText::TYPE_USER_NORMAL;
            $newTextTicket->save(false);

            $newTextFiles = new TicketsFiles();
            foreach ($newTicket->ticketFiles as $ticketFile) {
                $newTextFiles->ticket_id = $newTicket->id;
                $newTextFiles->ticket_text_id = $newTextTicket->id;
                $newTextFiles->file = 'upload/tickets/' . $ticketFile;
                $newTextFiles->save(false);
            }

            return $this->refresh();
        }

        $newTicketText = new TicketsText();
        if ($newTicketText->load(Yii::$app->request->post())) {
            $newTicketText->ticketsFiles = UploadedFile::getInstances($newTicketText, 'ticketsFiles');
            if ($newTicketText->ticketsFiles) {
                $this->uploadGallery($newTicketText, 'ticketsFiles','tickets');
            }

            $newTicketText->date_time = date('Y-m-d H:i:s');
            $newTicketText->user_type = TicketsText::TYPE_USER_NORMAL;
            $newTicketText->save(false);

            $newTextFiles = new TicketsFiles();
            foreach ($newTicketText->ticketsFiles as $ticketFile) {
                $newTextFiles->ticket_id = $newTicketText->ticket_id;
                $newTextFiles->ticket_text_id = $newTicketText->id;
                $newTextFiles->file = 'upload/tickets/' . $ticketFile;
                $newTextFiles->save(false);
            }

            return $this->refresh();
        }

        if ($id != '') {
            $openTicket = Tickets::find()->where(['tickets.id' => $id])->joinWith('user')
                ->joinWith('ticketsText.ticketsFiles')->asArray()->one();

            return $this->render('tickets', compact('newTicket', 'newTicketText', 'tickets', 'openTicket'));
        } else {
            return $this->render('tickets', compact('newTicket', 'newTicketText', 'tickets'));
        }
    }

    /**
     * Анкета пользователя
     * Данные организации, банка
     *
     * @return string
     */
    public function actionAccount() {
        if (Yii::$app->user->isGuest) {
            $this->render('auth');
        }

        $model = new UserSettingsForm;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/user/index']);
        }

        $model->loadData();

        $userId = Yii::$app->user->id;
        $profileSettings = UserSettings::findOne(['user_id' => $userId]);

        return $this->render('account', ['model' => $model, 'profileSettings' => $profileSettings]);
    }

    /**
     * Авторизация
     *
     * @return string|\yii\web\Response
     */
    public function actionAuth() {
        $model = new AuthForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['/user/index']);
        } else {
            $model->password = '';

            return $this->render('auth', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Настроки
     *
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function actionSettings() {
        if (Yii::$app->user->isGuest) {
            $this->render('auth');
        }

        $userId = Yii::$app->user->id;
        $userSettingsData = UserSettings::findOne(['user_id' => $userId]);
        $profileSettings = User::findOne(['id' => $userId]);

        $model = new UserProfileForm;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->image = UploadedFile::getInstance($model, 'image');

            if ($model->image) {
                $currentUserId = Yii::$app->user->id;
                $user = User::findOne(['id' => $currentUserId]);
                $image = $model->upload();
                if ($image != false) {
                    $user->avatar = $image;
                    $user->save();
                }
            }

            return $this->redirect(['/user/settings']);
        }

        $model->loadData();

        return $this->render('settings', ['userSettingsData' => $userSettingsData,
            'profileSettings' => $profileSettings, 'model' => $model]);
    }

    /**
     * Загрузка аватарки профиля
     *
     * @return string|\yii\web\Response
     */
    public function actionAvaUpload() {
        $model = new UploadAvatarForm;

        if (Yii::$app->request->isPost) {
            $currentUserId = Yii::$app->user->id;
            $user = User::findOne(['id' => $currentUserId]);

            $model->image = UploadedFile::getInstance($model, 'image');
            $image = $model->upload();
            if ($image != false) {
                $user->avatar = $image;
                $user->save();
            }

            return $this->redirect(['/user/ava-upload']);
        }

        return $this->render('avaUpload', ['model' => $model]);
    }

    /**
     * Выход
     *
     * @return \yii\web\Response
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
