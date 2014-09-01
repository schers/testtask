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
            [['date_use', 'card_id'], 'required'],
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
}
