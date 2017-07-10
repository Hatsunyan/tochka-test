<?php
/**
 * Интерфейс для хранилища, можно использовать люое хранилище
 */
namespace App\DataStorage;


interface IStorage
{
    const FIRST_STORAGE = 1;
    const SECOND_STORAGE = 2;

    public function __construct(int $state);

    /**
     * Возвращает все данные
     * @return array
     */
    public function getData() : array;

    /**
     * Устаналивает но не сохраняет данные
     * @param array $data
     * @return mixed
     */
    public function setData(array $data);

    /**
     * Сохраняет данные
     * @return bool
     */
    public function saveData(): bool;

    /**
     * Возращает один элемент
     * @param int $id
     * @return array|null
     */
    public function getItemById(int $id): ?array;

    /**
     * Очищает неактиное состояние для освобожения места
     */
    public function clearInactiveState() : void;

    /*
     * Создает хранилища
     */

    public function initStorage();
}