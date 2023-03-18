<?php

namespace MyProject\Models\Users;

use MyProject\Services\Db as Db;
use MyProject\Exceptions\InvalidArgumentException as InvalidArgumentException;

class User
{

    public $db;
    public function __construct()
    {
        $this->db = Db::getInstance();
    }

    public $id;
    /** @var string */
    //protected $nickname;

    /** @var string */
    public $email;

    /** @var int */
    protected $isConfirmed;

    /** @var string */
    private $role;//был протектед

    /** @var string */
    protected $passwordHash;

    /** @var string */
    public $authToken; //было protected, изменил для UsersController->activate  $user->authToken

    /** @var string */
    protected $createdAt;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    public function getId(): int
    {
        return $this->id;
    }
/*
    public function getNickName(): string
    {
        return $this->nickname;
    }
 * 
 */

    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    public function getRoleUser()
    {
        return $this->role;
    }
    
    
      public static function getById(int $id)
    {
       $db = Db::getInstance();
       
       try{
           $entities = $db->query(
               'SELECT * FROM users WHERE id=:id;',
               [':id' => $id],
               self::class );
       } catch (\Exception $e) {
           //echo $e->getMessage(); //в индексном файле будет выведена эта ошибка, так можно закоментить 
       }

       
       if(!$entities) {
           throw new \Exception('Пользователь удалён.');
           return;
       }
        return $entities ? $entities[0] : null;
    }
    
    public static function getByName(int $id) : string
    {
        $db = Db::getInstance();
        $authorName = $db->query(
            'SELECT nickname FROM users WHERE id=:id;',
            [':id' => $id]);
        
        return $authorName[0]['nickname'];
    }

    public static function signUp(array $userData): User
    {
         
        if (empty($userData['email'])) {
            throw new InvalidArgumentException('email.Вы не указали email');
        }
        
        if (empty($userData['password'])) {
            throw new InvalidArgumentException('password.Вы не указали пароль');
        }
        
         
         if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
             throw new InvalidArgumentException('email.Email некорректен');
         }
         
         if (mb_strlen($userData['password']) < 8) {
             throw new InvalidArgumentException('password.Пароль должен быть не менее 8 символов');
         }
        
         if (self::findOneByColumn('email', $userData['email']) !== null) {
             throw new InvalidArgumentException('email.Пользователь с таким email уже существует');
         }
          
        
         
         $user = new User();
         $user->email = $userData['email'];
         $user->passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);
         $user->isConfirmed = false;
         $user->role = 'user';
         $user->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
         $user->save();

         $user->id = $user->db->getLastInsertId();
        
         return $user;
         
         
    }

    public static function findOneByColumn(string $columnName, $value)
    {
        $db = Db::getInstance();
        $result = $db->query(
            'SELECT * FROM users WHERE ' . $columnName . ' = :value LIMIT 1;',
            [':value' => $value], self::class
        );
        if ($result === []) {
            return null;
        }
        return $result[0];
    }

    public function save()
    {

            $sql = 'INSERT INTO users (email, is_confirmed, user_role, password_hash, auth_token) VALUES
            (\'' . $this->getEmail() . '\', \'0\', \'user\', \'' . $this->passwordHash .'\', 
            \'' . $this->authToken .'\');';

            $this->db->query($sql, [], self::class);  
         
        
    }

    public function activate($id): void
    {
        $sql = 'UPDATE users
        SET is_confirmed = 1
        WHERE id =' .$id ;

       $this->db->query($sql);  
        
    }

    public static function login(array $loginData): User
    {
       
        if (empty($loginData['email'])) {
            throw new InvalidArgumentException('email.Не передан email');
        }
        
        if (empty($loginData['password'])) {
            throw new InvalidArgumentException('password.Не передан password');
        }
        
        $user = User::findOneByColumn('email', $loginData['email']); 
        
       
        
        if ($user === null) {
            throw new InvalidArgumentException('email.Нет пользователя с таким email');
        }
        
        if (!password_verify($loginData['password'], $user->password_hash)) {
            throw new InvalidArgumentException('password.Неправильный пароль');
        }
        if (!$user->is_confirmed) {
            throw new InvalidArgumentException('email.Пользователь не подтверждён');
        }
        
        
        $user->refreshAuthToken();

        $user->updateUserToken($user->getAuthToken(), $user->getId());
        
        return $user;
    }

    public function updateUserToken($token, $id){
        $sql = 'UPDATE users
        SET auth_token = \'' . $token . '\' WHERE id =' .$id ;

        $this->db->query($sql);  
    } 

    private function refreshAuthToken()
    {
        $this->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
    }
}
