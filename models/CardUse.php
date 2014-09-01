<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "card_use".
 *
 * @property string $id
 * @property string $date_use
 * @property string $description
 * @property double $cost
 * @property string $card_id
 *
 * @property Cards  $card
 */
class CardUse extends \yii\db\ActiveRecord
{
    /**
     * Старая стоимость
     *
     * @var number
     */
    protected $_oldCost;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'card_use';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCard(){
        return $this->hasOne(Cards::className(), ['id' => 'card_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_use', 'card_id', 'cost'], 'required'],
            [['date_use'], 'safe'],
            [['description'], 'string'],
            [['cost'], 'number'],
            [['card_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_use' => 'Дата использования',
            'description' => 'Описание операции',
            'cost' => 'Сумма',
            'card_id' => 'Карта',
        ];
    }

    /**
     * Сохраним старую сумму, она еще пригодится
     */
    public function afterFind(){
        $this->_oldCost = $this->cost;
        parent::afterFind();
    }

    /**
     * @param bool $insert
     *
     * @return bool
     */
    public function beforeSave($insert){
        if (parent::beforeSave($insert)){
            if ($insert){
                $balance = $this->card->sum - $this->cost;
            } else {
                $balance = $this->card->sum + $this->_oldCost - $this->cost;
            }
            if ($balance < 0){
                return false;
            } else {
                $this->card->sum = $balance;
                if($this->card->save()){
                    return true;
                }
            }
        }
        return false;
    }
}
