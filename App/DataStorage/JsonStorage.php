<?php


namespace App\DataStorage;


class JsonStorage implements IStorage
{
    protected $data;
    protected $state1 = '1.json';
    protected $state2 = '2.json';
    protected $usedState;

    protected $path = '/data/';


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
        $this->path = $_SERVER['DOCUMENT_ROOT'].$this->path;
    }

    public function getData(): array
    {
        if(!file_exists($this->path.$this->usedState))
        {
            throw new \Exception('Cant find state file');
        }
        $json = file_get_contents($this->path.$this->usedState);
        if($json == false)
        {
            throw new \Exception('Bad file');
        }
        $data = json_decode($json,true);
        if($data === null)
        {
            throw new \Exception('Bad json data');
        }
        return $data;
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function saveData(): bool
    {
        if(!file_exists($this->path))
        {
            mkdir($this->path,777);
        }
        $JSON = json_encode($this->data);
        $result = file_put_contents($this->path.$this->usedState,$JSON);
        return $result == false ? false : true;
    }

    public function getItemById(int $id): ?array
    {
        $data = $this->getData();
        return $data[$id] ?? null;
    }

    public function clearInactiveState(): void
    {
        $inactiveState = $this->usedState = $this->state1 ? $this->state2 : $this->state1;
        $result = unlink($this->path.$inactiveState);
        if(!$result)
        {
            throw new \Exception('Cant clear state');
        }
    }

    public function initStorage()
    {
        if(!file_exists($this->path))
        {
            mkdir($this->path,777);
        }
        $JSON = json_encode($this->data);
        file_put_contents($this->path.$this->state1,$JSON);
        file_put_contents($this->path.$this->state2,$JSON);
    }
}