<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class GenerateForm extends Model
{
    public $series;
    public $quantity;
    public $sum;
    public $period;

    const PER_1_YEAR = 0;
    const PER_6_MONTH = 1;
    const PER_1_MONTH = 2;

    public static function getPeriods(){
        return [
            self::PER_1_YEAR => '1 год',
            self::PER_6_MONTH => '6 месяцев',
            self::PER_1_MONTH => '1 месяц',
        ];
    }

    public function generateCards(){
        if ($this->validate()){
            /** @var Cards $lastCardBySeries */
            if($lastCardBySeries = Cards::find()
                ->where(['series' => $this->series])
                ->orderBy(['card_num' => SORT_DESC])->one()
            ){
                $numOffset = intval($lastCardBySeries->card_num) + 1;
            } else {
                $numOffset = 1;
            }

            for ($i = 0; $i < $this->quantity; $i++){
                $card = new Cards();
                $card->card_num = $i + $numOffset;
                $card->series = $this->series;
                $card->status = Cards::STATUS_ACTIVE;
                $card->sum = $this->sum;
                switch($this->period){
                    case self::PER_1_YEAR:
                        $card->date_end_activity = date('Y-m-d H:i:s', strtotime('+1 year'));
                        break;
                    case self::PER_6_MONTH:
                        $card->date_end_activity = date('Y-m-d H:i:s', strtotime('+6 month'));
                        break;
                    case self::PER_1_MONTH:
                        $card->date_end_activity = date('Y-m-d H:i:s', strtotime('+1 month'));
                        break;
                }
                if (!$card->save()){
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['series', 'quantity', 'sum', 'period'], 'required'],
            [['series', 'quantity', 'period'], 'integer'],
            [['sum'], 'number']
        ];
    }

    public function attributeLabels(){
        return [
            'series' => 'Серия',
            'quantity' => 'Количество',
            'sum' => 'Сумма',
            'period' => 'Период действия'
        ];
    }
}
