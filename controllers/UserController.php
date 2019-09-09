<?php

namespace app\controllers;

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
use DateTime;
use DateTimeZone;
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

    /**
     * @return array
     */
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
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionIndex() {
        $shops = Shops::find()->joinWith('tariff')->joinWith('shopsAdditions.addition')
            ->where(['shops.user_id' => Yii::$app->user->id, 'shops.deleted' => Shops::DELETED_FALSE])->asArray()->all();

        $tariffs = Tariff::find()->joinWith('tariffAdditionQty.addition tAQa')
            ->joinWith('tariffAddition.addition tAa')->indexBy('id')->asArray()->all();

        $monthly_payment = Service::find()->where(['user_id' => Yii::$app->user->id, 'agree' => Service::AGREE_TRUE])
            ->asArray()->all();

        $invoice = Payments::find()->where(['user_id' => Yii::$app->user->id, 'type' => Payments::TYPE_REFILL,
            'status' => Payments::STATUS_PAID])->andWhere(['!=', 'invoice_number', ''])->orderBy(['id' => SORT_DESC])
            ->limit(3)->asArray()->all();

        $tickets = Tickets::find()->joinWith('lastTicket')->where(['tickets.user_id' => Yii::$app->user->id,
            'tickets.status' => Tickets::STATUS_OPEN_TICKET])->limit(3)->asArray()->all();

        $modelShop = new Shops();
        $newTicket = new Tickets();

        if ($modelShop->load(Yii::$app->request->post())) {
            $modelShop->on_check = Shops::ON_CHECK_TRUE;
            $modelShop->save();

            Service::saveTariff($modelShop->tariff_id, $modelShop->id, Yii::$app->user->id);

            if (!empty($modelShop->addition)) {
                $ta_keys = array_keys($tariffs[$modelShop->tariff_id]['tariffAddition']);
                foreach ($modelShop->addition as $addition_one) {
                    $shopAddition = new ShopsAddition();
                    $shopAddition->shop_id = $modelShop->id;
                    $shopAddition->addition_id = $addition_one;
                    $shopAddition->quantity = 1;
                    $shopAddition->save();

                    if (in_array($addition_one, $ta_keys)) {
                        Service::saveAddition($addition_one, $modelShop->id, 1, Yii::$app->user->id, true);
                    } else {
                        Service::saveAddition($addition_one, $modelShop->id, 1, Yii::$app->user->id, false);
                    }
                }
            }

            return $this->refresh();
        }

        if ($newTicket->load(Yii::$app->request->post())) {
            $newTicket->status = Tickets::STATUS_OPEN_TICKET;
            $newTicket->new_text = false;
            $newTicket->ticketFiles = UploadedFile::getInstances($newTicket, 'ticketFiles');
            $newTicket->save(false);

            $newTextTicket = new TicketsText();
            $newTextTicket->ticket_id = $newTicket->id;
            $date_time = new DateTime('now', new DateTimeZone("UTC"));
            $newTextTicket->date_time = $date_time->format('Y-m-d H:i:s');
            $newTextTicket->text = $newTicket->tickets_text;
            $newTextTicket->user_type = TicketsText::TYPE_USER_NORMAL;
            $newTextTicket->save(false);

            if ($newTicket->ticketFiles) {
                $manyFile = $this->uploadGallery($newTicket, 'ticketFiles','tickets');

                foreach ($manyFile as $ticketFile) {
                    $newTextFiles = new TicketsFiles();
                    $newTextFiles->ticket_id = $newTicket->id;
                    $newTextFiles->ticket_text_id = $newTextTicket->id;
                    $newTextFiles->type_file = $ticketFile['type'];
                    $newTextFiles->file = $ticketFile['path'];
                    $newTextFiles->name_file = $ticketFile['name'];
                    $newTextFiles->save(false);
                }
            }

            return $this->refresh();
        }

        return $this->render('index', compact('shops', 'modelShop', 'tariffs',
            'invoice', 'newTicket', 'tickets', 'monthly_payment'));
    }

    /**
     * Просто action для обновления тарифа магазина
     *
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdateShop() {
        $updateShop = Yii::$app->request->post();

        $shop = Shops::findOne($updateShop['Shops']['id']);
        $oldTariff_id = $shop->tariff_id;
        $shop->tariff_id = $updateShop['Shops']['tariff_id'];
        $shop->on_check = Shops::ON_CHECK_TRUE;
        $shop->save(false);

        Service::saveTariff($updateShop['Shops']['tariff_id'], $updateShop['Shops']['id'], Yii::$app->user->id,
            $oldTariff_id, $updateShop['Shops']['edit_tariff_change']);

        return $this->redirect(['/user/index']);
    }

    /**
     * Просто action для изменения адреса магазина
     *
     * @return \yii\web\Response
     */
    public function actionEditShop() {
        $editShop = Yii::$app->request->post();

        $shop = Shops::findOne($editShop['Shops']['id']);
        $shop->address = $editShop['Shops']['address'];
        $shop->save(false);

        return $this->redirect(['/user/index']);
    }

    /**
     * Просто action для удаления магазина
     *
     * @return \yii\web\Response
     */
    public function actionDeleteShop() {
        $deleteShop = Yii::$app->request->post();

        $shop = Shops::findOne($deleteShop['Shops']['id']);
        $shop->deleted = Shops::DELETED_TRUE;
        $shop->save(false);

        Service::deleteShopService(Yii::$app->user->id, $deleteShop['Shops']['id']);

        return $this->redirect(['/user/index']);
    }

    /**
     * Просто action для изменения услуг магазина
     *
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionShopEditService() {
        $shopEditService = Yii::$app->request->post();

        $tariff_addition = Tariff::find()->joinWith('tariffAddition')->where([
            'tariff.id' => $shopEditService['Shops']['tariff_id'], 'tariff.status' => Tariff::STATUS_ON])->asArray()
            ->one();

        $tariff_addition_keys = array_keys($tariff_addition['tariffAddition']);

        $shopAdditions = ShopsAddition::find()->where(['shop_id' => $shopEditService['Shops']['id']])
            ->indexBy('addition_id')->all();
        $keys = array_keys($shopAdditions);

        foreach ($shopEditService['Shops']['addition'] as $key => $service) {
            if ($service != 0) {
                if (in_array($key, $keys)) {
                    $shopAdditions[$key]->quantity = $shopEditService['Shops']['quantityArr'][$key];
                    $shopAdditions[$key]->save();

                    if (in_array($key, $tariff_addition_keys)) {
                        if ($tariff_addition['tariffAddition'][$key]['quantity'] > $shopEditService['Shops']['quantityArr'][$key]) {
                            Service::updateAddition($key, $shopEditService['Shops']['id'], $shopEditService['Shops']['quantityArr'][$key], Yii::$app->user->id, true);
                        } else {
                            Service::updateAddition($key, $shopEditService['Shops']['id'], $shopEditService['Shops']['quantityArr'][$key], Yii::$app->user->id, true, $tariff_addition['tariffAddition'][$key]['quantity']);
                        }
                    } else {
                        Service::updateAddition($key, $shopEditService['Shops']['id'], $shopEditService['Shops']['quantityArr'][$key], Yii::$app->user->id);
                    }
                } else {
                    $shopAddition = new ShopsAddition();
                    $shopAddition->shop_id = $shopEditService['Shops']['id'];
                    $shopAddition->addition_id = $key;
                    $shopAddition->quantity = $shopEditService['Shops']['quantityArr'][$key];
                    $shopAddition->save();

                    if (in_array($key, $tariff_addition_keys)) {
                        if ($tariff_addition['tariffAddition'][$key]['quantity'] > $shopEditService['Shops']['quantityArr'][$key]) {
                            Service::saveAddition($key, $shopEditService['Shops']['id'], $shopEditService['Shops']['quantityArr'][$key], Yii::$app->user->id, true);
                        } else {
                            Service::saveAddition($key, $shopEditService['Shops']['id'], $shopEditService['Shops']['quantityArr'][$key], Yii::$app->user->id, true, $tariff_addition['tariffAddition'][$key]['quantity']);
                        }
                    } else {
                        Service::saveAddition($key, $shopEditService['Shops']['id'], $shopEditService['Shops']['quantityArr'][$key], Yii::$app->user->id);
                    }
                }
            } else {
                if (in_array($key, $keys)) {
                    $shopAdditions[$key]->delete();
                    Service::deleteAddition($key, $shopEditService['Shops']['id'], Yii::$app->user->id);
                }
            }
        }

        $shop = Shops::findOne($shopEditService['Shops']['id']);
        $shop->on_check = Shops::ON_CHECK_TRUE;
        $shop->save(false);

        return $this->redirect(['/user/index']);
    }

    /**
     * Страница детализации баланса
     *
     * @param int $d
     * @param int $i
     *
     * @param int $h
     *
     * @return string
     */
    public function actionPayment($d = 1, $i = 1, $h = 1) {
        $modelPaid = new NewPaid();
        $payments = Payments::find()->joinWith('shop')->joinWith('tariff')->joinWith('addition')
            ->where(['payments.user_id' => Yii::$app->user->id, 'payments.type' => Payments::TYPE_WRITEOFF,
                'payments.status' => Payments::STATUS_PAID])->orderBy(['payments.id' => SORT_DESC])
            ->limit(3 * $h)->asArray()->all();

        $payments_count = Payments::find()->where(['user_id' => Yii::$app->user->id, 'type' => Payments::TYPE_WRITEOFF,
                'status' => Payments::STATUS_PAID])->orderBy(['id' => SORT_DESC])->count();

        $deposit = Payments::find()->where(['user_id' => Yii::$app->user->id, 'type' => Payments::TYPE_REFILL,
            'status' => Payments::STATUS_PAID])->orderBy(['id' => SORT_DESC])->limit(3 * $d)->asArray()->all();

        $deposit_count = Payments::find()->where(['user_id' => Yii::$app->user->id, 'type' => Payments::TYPE_REFILL,
            'status' => Payments::STATUS_PAID])->orderBy(['id' => SORT_DESC])->count();

        $invoice = Payments::find()->where(['user_id' => Yii::$app->user->id, 'type' => Payments::TYPE_REFILL])
            ->andWhere(['!=', 'invoice_number', ''])->orderBy(['id' => SORT_DESC])->limit(3 * $i)->asArray()->all();

        $invoice_count = Payments::find()->where(['user_id' => Yii::$app->user->id, 'type' => Payments::TYPE_REFILL])
            ->andWhere(['!=', 'invoice_number', ''])->orderBy(['id' => SORT_DESC])->count();

        $maxPaymentId = Payments::getMaxId();

        return $this->render('payment', compact('d', 'i', 'h', 'modelPaid', 'payments', 'deposit',
            'invoice', 'maxPaymentId', 'deposit_count', 'invoice_count', 'payments_count'));
    }

    /**
     * Выставление счета
     *
     * @return string
     * @throws \Exception
     */
    public function actionSavePdf() {
        $sumToPdf = Yii::$app->request->post();
        if ($sumToPdf['NewPaid']['amount'] != 0) {
            $maxPaymentNumber = Payments::getMaxNumberSchet();
            $date_time = new DateTime('now');
            $date = $date_time->format('d.m.Y');
            $number = 'E' . ($maxPaymentNumber['invoice_number'] + 1);

            $schetPayment = new Payments();
            $schetPayment->user_id = Yii::$app->user->id;
            $schetPayment->shop_id = 0;
            $schetPayment->type_service = 0;
            $schetPayment->service_id = 0;
            $schetPayment->type = Payments::TYPE_REFILL;
            $schetPayment->way = Payments::WAY_SCHET;
            $schetPayment->date = $date_time->format('Y-m-d');
            $schetPayment->invoice_number = $maxPaymentNumber['invoice_number'] + 1;
            $schetPayment->invoice_date = $date_time->format('Y-m-d');
            $schetPayment->amount = $sumToPdf['NewPaid']['amount'];
            $schetPayment->status = Payments::STATUS_EXPOSED;
            $schetPayment->description = 'Пополнение баланса через счет';
            $schetPayment->save(false);

            $pdfFile = Yii::$app->pdf;
            $mpdf = $pdfFile->api;
            $mpdf->SetHeader('Счёт №' . $number . ' от ' . $date);
            $mpdf->SetTitle('Счёт №' . $number . ' от ' . $date);

            $user = User::find()->joinWith('userSetting')->where(['user.id' => Yii::$app->user->id])->asArray()
                ->limit(1)->one();
            $content = $this->renderPartial('_schetPDF', ['number' => $number, 'date' => $date, 'user' => $user,
                'amount' => $sumToPdf['NewPaid']['amount']]);
            $mpdf->WriteHtml($content);
            $filename = 'Счёт №' . $number . ' от ' . $date . '.pdf';
            return $mpdf->Output($filename, 'D');
        } else {
            return false;
        }
    }

    /**
     * Скачивание счета счета
     *
     * @param $id
     * @param $invoice_number
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDownloadPdf($id, $invoice_number) {
        $schetPayment = Payments::find()->where(['id' => $id, 'invoice_number' => $invoice_number])->limit(1)->one();
        $user = User::find()->joinWith('userSetting')->where(['user.id' => Yii::$app->user->id])->asArray()
            ->limit(1)->one();

        $date = Yii::$app->formatter->asDate($schetPayment['invoice_date']);
        $number = 'E' . $schetPayment['invoice_number'];

        $pdfFile = Yii::$app->pdf;
        $mpdf = $pdfFile->api;
        $mpdf->SetHeader('Счёт №' . $number . ' от ' . $date);
        $mpdf->SetTitle('Счёт №' . $number . ' от ' . $date);

        $content = $this->renderPartial('_schetPDF', ['number' => $number, 'date' => $date, 'user' => $user,
            'amount' => $schetPayment['amount']]);
        $mpdf->WriteHtml($content);
        $filename = 'Счёт №' . $number . ' от ' . $date . '.pdf';
        return $mpdf->Output($filename, 'I');
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
               $user = User::find()->joinWith('userSetting')->where(['user.id' => Yii::$app->user->id])->asArray()
                   ->limit(1)->one();

               $pdf = Yii::$app->pdf;
               $mpdf = $pdf->api;
               $content = $this->renderPartial('_actPDF', ['model' => $model, 'user' => $user]);
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
                    $date_time = new DateTime('now');
                    $payments->date = $date_time->format('Y-m-d');
                    $payments->invoice_date = $date_time->format('Y-m-d');
                    $payments->amount = $resp_array->transaction->amount;
                    $payments->description = $resp_array->transaction->description;

                    if ($resp_array->transaction->state == 'COMPLETE') {
                        $user->balance += $resp_array->transaction->amount;
                        $user->save(false);

                        Yii::$app->session->setFlash('success', 'Ваша оплата на ' . Yii::$app->formatter
                                ->asDecimal($resp_array->transaction->amount, 2) . ' руб. прошла успешно.');

                        $payments->status = Payments::STATUS_PAID;
                    } elseif ($resp_array->transaction->state == 'PROCESSING' || $resp_array->transaction->state == 'WAITING_FOR_3DS') {
                        Yii::$app->session->setFlash('success', 'Оплата находится в процессе. Как только статус изменится, деньги постуят на ваш счет в течении 5 минут.');

                        $payments->status = Payments::STATUS_WAITING;
                    }  elseif ($resp_array->transaction->state == 'FAILED') {
                        Yii::$app->session->setFlash('error', 'Во время оплаты произошла неизвестная ошибка. Попробуйте повторить операцию.');

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
                Yii::$app->session->setFlash('error', 'Во время оплаты произошла неизвестная ошибка. Попробуйте повторить операцию.');
                return $this->redirect(['/user/payment', 'd' => 1, 'i' => 1]);
            }
        }

        Yii::$app->session->setFlash('error', 'Во время оплаты произошла неизвестная ошибка. Попробуйте повторить операцию.');
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

        Yii::$app->session->setFlash('error', 'Во время оплаты произошла неизвестная ошибка. Попробуйте повторить операцию.');
        return $this->redirect(['/user/payment', 'd' => 1, 'i' => 1]);
    }

    /**
     * Страница тех. потдержки
     *
     * @param string $id
     *
     * @return string
     * @throws \Exception
     */
    public function actionTickets($id = '') {
        $tickets = Tickets::find()->where(['user_id' => Yii::$app->user->id, 'status' => Tickets::STATUS_OPEN_TICKET])
            ->joinWith('lastTicket')->asArray()->all();

        $newTicket = new Tickets();
        if ($newTicket->load(Yii::$app->request->post())) {
            $newTicket->status = Tickets::STATUS_OPEN_TICKET;
            $newTicket->new_text = false;
            $newTicket->ticketFiles = UploadedFile::getInstances($newTicket, 'ticketFiles');
            $newTicket->save(false);

            $newTextTicket = new TicketsText();
            $newTextTicket->ticket_id = $newTicket->id;
            $date_time = new DateTime('now', new DateTimeZone("UTC"));
            $newTextTicket->date_time = $date_time->format('Y-m-d H:i:s');
            $newTextTicket->text = $newTicket->tickets_text;
            $newTextTicket->user_type = TicketsText::TYPE_USER_NORMAL;
            $newTextTicket->save(false);

            if ($newTicket->ticketFiles) {
                $manyFile = $this->uploadGallery($newTicket, 'ticketFiles','tickets');

                foreach ($manyFile as $ticketFile) {
                    $newTextFiles = new TicketsFiles();
                    $newTextFiles->ticket_id = $newTicket->id;
                    $newTextFiles->ticket_text_id = $newTextTicket->id;
                    $newTextFiles->type_file = $ticketFile['type'];
                    $newTextFiles->file = $ticketFile['path'];
                    $newTextFiles->name_file = $ticketFile['name'];
                    $newTextFiles->save(false);
                }
            }

            return $this->refresh();
        }

        $newTicketText = new TicketsText();
        if ($newTicketText->load(Yii::$app->request->post())) {
            $newTicketText->ticketsFiles = UploadedFile::getInstances($newTicketText, 'ticketsFiles');
            $date_time = new DateTime('now', new DateTimeZone("UTC"));
            $newTicketText->date_time = $date_time->format('Y-m-d H:i:s');
            $newTicketText->user_type = TicketsText::TYPE_USER_NORMAL;
            $newTicketText->save(false);

            if ($newTicketText->ticketsFiles) {
                $manyFiles = $this->uploadGallery($newTicketText, 'ticketsFiles', 'tickets');

                foreach ($manyFiles as $ticketFile) {
                    $newTextFiles = new TicketsFiles();
                    $newTextFiles->ticket_id = $newTicketText->ticket_id;
                    $newTextFiles->ticket_text_id = $newTicketText->id;
                    $newTextFiles->type_file = $ticketFile['type'];
                    $newTextFiles->file = $ticketFile['path'];
                    $newTextFiles->name_file = $ticketFile['name'];
                    $newTextFiles->save(false);
                }
            }

            return $this->refresh();
        }

        if ($id != '') {
            $openTicket = Tickets::find()->where(['tickets.id' => $id])->joinWith('ticketsText.ticketsFiles')
                ->asArray()->one();

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
            return $this->refresh();
        }

        $model->loadData();
        $profileSettings = UserSettings::find()->where(['user_id' => Yii::$app->user->id])->asArray()->limit(1)
            ->one();

        return $this->render('account', compact('model', 'profileSettings'));
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

        $profileSettings = User::find()->with('userSetting')->where(['id' => Yii::$app->user->id])->asArray()
            ->limit(1)->one();

        $model = new UserProfileForm;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->image = UploadedFile::getInstance($model, 'image');

            if ($model->image) {
                $currentUserId = Yii::$app->user->id;
                $user = User::findOne(['id' => $currentUserId]);
                $image = $this->uploadImage($model, 'image', 'user', $user->avatar);
                $user->avatar = $image;
                $user->save();
            }

            return $this->redirect(['/user/settings']);
        }

        $model->loadData();

        return $this->render('settings', compact('profileSettings', 'model'));
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
            if ($model->image) {
                $image = $this->uploadImage($model, 'image', 'user', $user->avatar);
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
