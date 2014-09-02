<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use app\models\CardUse;

/**
 * This is the model class for table "cards".
 *
 * @property string    $id
 * @property string    $series
 * @property string    $card_num
 * @property string    $date_release
 * @property string    $date_end_activity
 * @property double    $sum
 * @property integer   $status
 * @property integer   $creator_id
 *
 * @property CardUse[] $carduses
 * @property User      $creator
 * @property string    $serialnum
 */
class Cards extends ActiveRecord
{

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_EXPIRED = 2;

    /**
     * @var string Читабельный статус.
     */
    protected $_status;

    protected $_serialnum;

    protected static $currentTime;

    /**
     * @return int
     */
    protected static function getCurrentTime(){
        if (self::$currentTime == null){
            self::$currentTime = time();
        }
        return self::$currentTime;
    }

    /**
     * Записи по использованию карты
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarduses(){

        return $this->hasMany(CardUse::className(), ['card_id' => 'id']);
    }

    public function getCreator(){
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    /**
     * Серия и номер карты
     *
     * @return string
     */
    public function getSerialnum(){
        if ($this->_serialnum == null){
            $this->_serialnum = 'серия: '.$this->series.', №'.$this->card_num;
        }
        return $this->_serialnum;
    }

    /**
     * @return string Читабельный статус.
     */
    public function getStatus()
    {
        if ($this->_status === null) {
            $statuses = self::getStatusArray();
            $this->_status = $statuses[$this->status];
        }
        return $this->_status;
    }

    /**
     * @return array Массив доступных статусов.
     */
    public static function getStatusArray()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'Card_active'),
            self::STATUS_INACTIVE => Yii::t('app', 'Card_inactive'),
            self::STATUS_EXPIRED => Yii::t('app', 'Card_expired')
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date_release'],
                ],
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cards';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['series', 'card_num', 'status', 'creator_id'], 'integer'],
            [['sum','series', 'card_num', 'status'], 'required'],
            [['date_release', 'date_end_activity'], 'safe'],
            [['sum'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'series' => 'Серия',
            'card_num' => 'Номер',
            'date_release' => 'Дата выпуска',
            'date_end_activity' => 'Дата окончания активности',
            'sum' => 'Сумма',
            'status' => 'Статус',
            'serialnum' => 'Карта',
            'creator_id' => 'Добавил'
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterFind(){
        if (strtotime($this->date_end_activity) < self::getCurrentTime()){
            $this->status = self::STATUS_EXPIRED;
            $this->save();
        } else if ($this->status == self::STATUS_EXPIRED) {
            $this->status = self::STATUS_INACTIVE;
            $this->save();
        }
        parent::afterFind();
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete(){
        if (parent::beforeDelete()){
            if($items = CardUse::find()->where(['card_id' => $this->id])->all()){
                $success = true;
                foreach($items as $item){
                    if (!$item->delete()){
                        $success = false;
                    }
                }
                return $success;
            }
        }
        return false;
    }
}
