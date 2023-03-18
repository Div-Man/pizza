<?php 

namespace MyProject\Services;

class Db{

    private static $instancesCount = 0;
    private static $instance;
    public $pdo;
    
    public function __construct()
    {
        $dbOptions = (require __DIR__ . '../../../settings.php')['db'];
        $dsn = "mysql:host=" . $dbOptions['host'] . ";port=5432;dbname=" . $dbOptions['dbname'] . ";";   
        $this->pdo = new \PDO($dsn, $dbOptions['user'], $dbOptions['password']);
         self::$instancesCount++;
    }

    
    public function query(string $sql, $params = [], $class = 'stdClass')
    {
       
        $sth = $this->pdo->prepare($sql);
       
        if(isset($params[1]) && $params[1] === 'in'){ //для запроса, в котром выборка по IN 
             $result = $sth->execute(explode(',', $params[0]));
        }
        else {
            $result = $sth->execute($params);
            
        }

        if (false === $result) {
            return null;
        }
         
        
         return $sth->fetchAll(\PDO::FETCH_CLASS, $class);
    }
    

     public function update(string $sql, $arr)
     {
          $sth = $this->pdo->prepare($sql);
          $sth->execute($arr);
          $rowCount = $sth->rowCount();
         return $rowCount;
     }
    
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    
    public static function getInstancesCount(): int
    {
        return self::$instancesCount;
    }

    public function getLastInsertId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }
}
