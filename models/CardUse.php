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
            'date_use' => 'Date Use',
            'description' => 'Description',
            'cost' => 'Cost',
            'card_id' => 'Card ID',
        ];
    }
}
