<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;
use yii\helpers\Security;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string  $username      Никнейм
 * @property string  $email         Email
 * @property string  $name          Имя
 * @property string  $surname       Фамилия
 * @property string  $password_hash Зашифрованный пароль
 * @property string  $auth_key      Ключ активации учётной записи
 * @property integer $role_id       Роль
 * @property integer $status_id     Статус
 * @property integer $create_time   Время создания
 * @property integer $update_time   Время последнего обновления
 *
 * @property string  $password      Пароль в чистом виде
 * @property string  $repassword    Повторный пароль
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * Статусы записей модели.
     * - Неактивный
     * - Активный
     * - Забанненый
     * - Удаленный
     */
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BANNED = 2;
    const STATUS_DELETED = 3;

    /**
     * Роли пользователей.
     */
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    const ROLE_SUPERADMIN = 'superadmin';

    /**
     * События модели.
     */
    const EVENT_AFTER_VALIDATE_SUCCESS = 'afterValidateSuccess';

    /**
     * Ключи кэша которые использует модель.
     */
    const CACHE_USERS_LIST_DATA = 'usersListData';

    /**
     * Переменная используется для сбора пользовательской информации, но не сохраняется в базу.
     * @var string $password Пароль
     */
    public $password;

    /**
     * Переменная используется для сбора пользовательской информации, но не сохраняется в базу.
     * @var string $repassword Повторный пароль
     */
    public $repassword;

    /**
     * Вспомогательная приватная переменная.
     * @var string $_fio ФИО
     */
    protected $_fio;

    /**
     * @var string Читабельная роль пользователя.
     */
    protected $_role;

    /**
     * @var string Читабельный статус пользователя.
     */
    protected $_status;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'update_time',
                ],
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Логин [[username]]
            ['username', 'filter', 'filter' => 'trim', 'on' => ['signup', 'admin-update', 'admin-create']],
            ['username', 'required', 'on' => ['signup', 'admin-update', 'admin-create']],
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'on' => ['signup', 'admin-update', 'admin-create']],
            ['username', 'string', 'min' => 3, 'max' => 30, 'on' => ['signup', 'admin-update', 'admin-create']],
            ['username', 'unique', 'on' => ['signup', 'admin-update', 'admin-create']],

            // E-mail [[email]]
            ['email', 'filter', 'filter' => 'trim', 'on' => ['signup', 'resend', 'recovery', 'admin-update', 'admin-create']],
            ['email', 'required', 'on' => ['signup', 'resend', 'recovery', 'admin-update', 'admin-create']],
            ['email', 'email', 'on' => ['signup', 'resend', 'recovery', 'admin-update', 'admin-create']],
            ['email', 'string', 'max' => 100, 'on' => ['signup', 'resend', 'recovery', 'admin-update', 'admin-create']],
            ['email', 'unique', 'on' => ['signup', 'admin-update', 'admin-create']],
            ['email', 'exist', 'on' => ['resend', 'recovery'], 'message' => Yii::t('users', 'Пользователь с указанным адресом не существует.')],

            // Пароль [[password]]
            ['password', 'required', 'on' => ['signup', 'login', 'password', 'admin-create']],
            ['password', 'string', 'min' => 6, 'max' => 30, 'on' => ['signup', 'login', 'password', 'admin-update', 'admin-create']],
            ['password', 'compare', 'compareAttribute' => 'oldpassword', 'operator' => '!==', 'on' => 'password'],

            // Подтверждение пароля [[repassword]]
            ['repassword', 'required', 'on' => ['signup', 'password', 'admin-create']],
            ['repassword', 'string', 'min' => 6, 'max' => 30, 'on' => ['signup', 'password', 'admin-update', 'admin-create']],
            ['repassword', 'compare', 'compareAttribute' => 'password', 'on' => ['signup', 'password', 'admin-create']],
            ['repassword', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'on' => 'admin-update'],

            // Имя и Фамилия [[name]] & [[surname]]
            [['name', 'surname'], 'required', 'on' => ['signup', 'update', 'admin-update', 'admin-create']],
            [['name', 'surname'], 'string', 'max' => 50, 'on' => ['signup', 'update', 'admin-update', 'admin-create']],
            ['name', 'match', 'pattern' => '/^[a-zа-яё]+$/iu', 'on' => ['signup', 'update', 'admin-update', 'admin-create']],
            ['surname', 'match', 'pattern' => '/^[a-zа-яё]+(-[a-zа-яё]+)?$/iu', 'on' => ['signup', 'update', 'admin-update', 'admin-create']],

            // Роль [[role_id]]
            ['role_id', 'in', 'range' => array_keys(self::getRoleArray()), 'on' => ['admin-update', 'admin-create']],

            // Статус [[status_id]]
            ['status_id', 'in', 'range' => array_keys(self::getStatusArray()), 'on' => ['admin-update', 'admin-create']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'username'      => 'Логин',
            'email'         => 'Email',
            'name'          => 'Имя',
            'surname'       => 'Фамилия',
            'password_hash' => 'Хэш пароля',
            'password'      => 'Пароль',
            'repassword'    => 'Повторите пароль',
            'role_id'       => 'Роль',
            'status_id'     => 'Статус',
            'create_time'   => 'Дата создания',
            'update_time'   => 'Дата последнего обновления',
        ];
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     *
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given secrete token.
     *
     * @param string $token the secrete token
     *
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     *
     * @return string|integer an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     *
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Выбор пользователя по [[username]]
     * @param string $username
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findByUsername($username)
    {
        return static::find()->where('username = :username', [':username' => $username])->one();
    }

    /**
     * Выборка админа по [[username]]
     * @param string $username
     *
     * @return array|null|ActiveRecord
     */
    public static function findActiveAdminByUsername($username)
    {
        return static::find()->where([
                'and', 'username = :username',
                ['or', 'role_id = ' . self::ROLE_ADMIN,  'role_id = ' . self::ROLE_SUPERADMIN]
            ], [':username' => $username])->one();
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     *
     * @param string $authKey the given auth key
     *
     * @return boolean whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Валидация пароля.
     * @param string $password
     * @return boolean
     */
    public function validatePassword($password)
    {
        return Security::validatePassword($password, $this->password_hash);
    }

    /**
     * @param bool $username
     *
     * @return string Полное имя пользователя.
     */
    public function getFio($username = false)
    {
        if ($this->_fio === null) {
            $this->_fio = $this->name . ' ' . $this->surname;
            if ($username !== false) {
                $this->_fio .= ' [' . $this->username . ']';
            }
        }
        return $this->_fio;
    }

    /**
     * @return string Читабельные роли пользователя.
     */
    public function getRole()
    {
        if ($this->_role === null) {
            $roles = self::getRoleArray();
            $this->_role = $roles[$this->role_id];
        }
        return $this->_role;
    }

    /**
     * @return array Массив доступных ролей пользователя.
     */
    public static function getRoleArray()
    {
        return [
            self::ROLE_USER => Yii::t('app', 'Normal user'),
            self::ROLE_ADMIN => Yii::t('app', 'Admin'),
            self::ROLE_SUPERADMIN => Yii::t('app', 'Super-admin')
        ];
    }

    /**
     * @return string Читабельный статус пользователя.
     */
    public function getStatus()
    {
        if ($this->_status === null) {
            $statuses = self::getStatusArray();
            $this->_status = $statuses[$this->status_id];
        }
        return $this->_status;
    }

    /**
     * @return array Массив доступных ролей пользователя.
     */
    public static function getStatusArray()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            self::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
            self::STATUS_BANNED => Yii::t('app', 'Banned')
        ];
    }

    /**
     * @return array [[DropDownList]] массив пользователей.
     */
    public static function getUserArray()
    {
        $key = self::CACHE_USERS_LIST_DATA;
        $value = Yii::$app->getCache()->get($key);
        if ($value === false || empty($value)) {
            $value = self::find()->select(['id', 'username'])->orderBy('username ASC')->asArray()->all();
            $value = ArrayHelper::map($value, 'id', 'username');
            Yii::$app->cache->set($key, $value);
        }
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = [
            // Frontend scenarios
            'signup' => ['name', 'surname', 'username', 'email', 'password', 'repassword'],
            'activation' => [],
            'login' => ['username', 'password'],
            'update' => ['name', 'surname'],
            'delete' => [],
            'resend' => ['email'],
            'recovery' => ['email'],
            // Backend scenarios
            'admin-update' => ['name', 'surname', 'username', 'email', 'password', 'repassword', 'status_id', 'role_id'],
            'admin-create' => ['name', 'surname', 'username', 'email', 'password', 'repassword', 'status_id', 'role_id']
        ];

        return array_merge($scenarios, parent::scenarios());
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Проверяем если это новая запись
            if ($this->isNewRecord) {
                // Хэшируем пароль
                if (!empty($this->password)) {
                    $this->password_hash = Security::generatePasswordHash($this->password);
                }
                // Задаём статус записи
                if (!$this->status_id) {
                    $this->status_id = self::STATUS_ACTIVE;
                }
                //Задаем роль
                if (!$this->role_id){
                    $this->role_id = self::ROLE_USER;
                }
                // Генерируем уникальный ключ
                $this->auth_key = Security::generateRandomKey();
            } else {
//                // Активируем пользователя если был отправлен запрос активации
//                if ($this->scenario === 'activation') {
//                    $this->status_id = self::STATUS_ACTIVE;
//                    $this->auth_key = Security::generateRandomKey();
//                }
//                // Обновляем пароль и ключ если был отправлен запрос восстановления пароля
//                if ($this->scenario === 'recovery') {
//                    $this->password = Security::generateRandomKey(8);
//                    $this->auth_key = Security::generateRandomKey();
//                    $this->password_hash = Security::generatePasswordHash($this->password);
//                }
//                // Обновляем пароль если был отправлен запрос для его смены
//                if ($this->scenario === 'password') {
//                    $this->password_hash = Security::generatePasswordHash($this->password);
//                }
                // При редактировании пароля пользователя в админке, генерируем password_hash
                if ($this->scenario === 'admin-update' && !empty($this->password)) {
                    $this->password_hash = Security::generatePasswordHash($this->password);
                }
                // Удаляем пользователя
                if ($this->scenario === 'delete') {
                    $this->status_id = self::STATUS_DELETED;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * @param bool $insert
     */
    public function afterSave($insert)
    {
        //Подключаем RBAC сценарий
        $auth = Yii::$app->authManager;

        if (!$insert){
            $auth->revokeAll($this->getId());
        }

        switch($this->role_id){
            case self::ROLE_SUPERADMIN :
                $role = $auth->getRole(self::ROLE_SUPERADMIN);
                break;
            case self::ROLE_ADMIN :
                $role = $auth->getRole(self::ROLE_ADMIN);
                break;
            case self::ROLE_USER :
            default:
                $role = $auth->getRole(self::ROLE_USER);
        }

        $auth->assign($role, $this->getId());

        // Удаляем все записи пользователя.
        if ($this->scenario === 'delete') {

        }
        parent::afterSave($insert);
    }
}
