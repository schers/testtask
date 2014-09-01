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
 *
 * @property CardUse[] $carduses
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

    public function getCarduses(){

        return $this->hasMany(CardUse::className(), ['card_id' => 'id']);
    }

    public function getSerialnum(){
        return 'серия: '.$this->series.', №'.$this->card_num;
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
            [['series', 'card_num', 'status'], 'integer'],
            [['sum'], 'required'],
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
            'serialnum' => 'Карта'
        ];
    }
}
