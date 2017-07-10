<?php


namespace App\DataStorage;


use App\Config;

class MysqlStorage implements IStorage
{

    protected $data;
    protected $state1 = 'table1';
    protected $state2 = 'table2';
    protected $usedState;

    protected $connection;

    public function __construct(int $state)
    {
        switch ($state)
        {
            case 1:
                $this->usedState = $this->state1;
                break;
            case 2:
                $this->usedState = $this->state2;
                break;
            default:
                throw new \Exception('Cant find state');
        }
        $dsn = 'mysql:dbname='.Config::getMysqlDatabase().';host=127.0.0.1';
        $this->connection = new \PDO(
            $dsn,
            Config::getMysqlUser(),
            Config::getMysqlPassword());


    }
    public function getData() : array
    {
        $arr = [];
        $result = $this->connection->query('SELECT id,title,`date` FROM '.$this->usedState);
        while ($row = $result->fetch(\PDO::FETCH_ASSOC))
        {
            $arr[$row['id']] = $row;
        }
        return $arr;
    }
    public function setData(array $data)
    {
         $this->data = $data;
    }
    public function saveData(): bool {
         $sql = 'INSERT INTO '.$this->usedState.'
          SET
          id = :id,
          title = :title,
          `date` = :date,
          author = :author,
          status = :status,
          description = :description
          ';
         $stmt = $this->connection->prepare($sql);
         foreach ($this->data as $d)
         {
             $stmt->bindParam(':id',$d['id'],\PDO::PARAM_INT);
             $stmt->bindParam(':title',$d['title'],\PDO::PARAM_STR);
             $stmt->bindParam(':date',$d['date'],\PDO::PARAM_STR);
             $stmt->bindParam(':author',$d['author'],\PDO::PARAM_STR);
             $stmt->bindParam(':status',$d['status'],\PDO::PARAM_STR);
             $stmt->bindParam(':description',$d['description'],\PDO::PARAM_STR);
             $stmt->execute();
         }
         if($stmt->errorInfo()[0] != '00000')
         {
             throw new \Exception($stmt->errorInfo()[2]);
         }
         return true;
    }
    public function getItemById(int $id): ?array
    {
        $result = $this->connection->query('SELECT * FROM '.$this->usedState.' WHERE id = '.$id);
        return $result->fetch(\PDO::FETCH_ASSOC);
    }
    public function clearInactiveState() : void
    {
        $inactiveState = $this->usedState = $this->state1 ? $this->state2 : $this->state1;
        $this->connection->query('TRUNCATE TABLE '.$inactiveState);
    }


    public function initStorage()
    {
        $sql1 = 'CREATE TABLE '.$this->state1.' (
              `id` int(10) UNSIGNED NOT NULL,
              `title` varchar(255) NOT NULL,
              `date` timestamp NOT NULL,
              `author` varchar(255) NOT NULL,
              `status` varchar(255) NOT NULL,
              `description` text NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
        $sql2 = 'CREATE TABLE '.$this->state2.' (
              `id` int(10) UNSIGNED NOT NULL,
              `title` varchar(255) NOT NULL,
              `date` timestamp NOT NULL,
              `author` varchar(255) NOT NULL,
              `status` varchar(255) NOT NULL,
              `description` text NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
        $this->connection->query($sql1);
        $this->connection->query($sql2);
        $this->connection->query('ALTER TABLE '.$this->state1.'  ADD PRIMARY KEY (`id`);');
        $this->connection->query('ALTER TABLE '.$this->state1.'  ADD PRIMARY KEY (`id`);');
        $this->saveData();
        $this->usedState = $this->usedState === $this->state1 ? $this->state2:$this->state2;
        $this->saveData();
    }
}