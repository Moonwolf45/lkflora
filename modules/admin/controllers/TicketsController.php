<?php

namespace app\modules\admin\controllers;


use app\models\tickets\Tickets;
use app\models\tickets\TicketsFiles;
use app\models\tickets\TicketsSearch;
use app\models\tickets\TicketsText;
use app\models\traits\UploadFilesTrait;
use DateTime;
use DateTimeZone;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\UploadedFile;

class TicketsController extends Controller {
    use UploadFilesTrait;

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex() {
        $searchModel = new TicketsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tickets model.
     *
     * @param $id
     *
     * @return string
     */
    public function actionView($id) {
        $model = Tickets::find()->joinWith('user')->where(['tickets.id' => $id])->limit(1)->one();
        $tickets_text = TicketsText::find()->joinWith('ticketsFiles')->where(['tickets_text.ticket_id' => $id])
            ->asArray()->all();

        return $this->render('view', compact('model', 'tickets_text'));
    }

    /**
     * @param $id
     * @param $shop_id
     *
     * @return string
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdate($id) {

        $newTicketText = new TicketsText();
        if ($newTicketText->load(Yii::$app->request->post())) {
            $newTicketText->ticketsFiles = UploadedFile::getInstances($newTicketText, 'ticketsFiles');
            $date_time = new DateTime('now', new DateTimeZone("UTC"));
            $newTicketText->date_time = $date_time->format('Y-m-d H:i:s');
            $newTicketText->user_type = TicketsText::TYPE_USER_TICKETS;
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

            $new_text = Tickets::findOne($newTicketText->ticket_id);
            $new_text->new_text = true;
            $new_text->save(false);

            return $this->refresh();
        }


        $openTicket = Tickets::find()->where(['tickets.id' => $id])->joinWith('ticketsText.ticketsFiles')
            ->asArray()->one();

        return $this->render('update', compact('newTicketText', 'openTicket'));
    }

    /**
     * @param $id
     *
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id) {
        $files = TicketsFiles::find()->where(['ticket_id' => $id])->all();
        $this->deleteImages($files, 'file', 'array');
        Tickets::findOne($id)->delete();

        return $this->redirect(['index']);
    }

}
