<?php

/**
 * Класс для работы с таблицей products
 *  в конструктор передаются параметры для подключения к БД Mysql
 *  в дальнейшем могут быть добавлены функции создания, изменения и удаления товаров
 */
class Products
{
    private PDO $connection;

    public function __construct($host, $port, $dbname, $user, $password)
    {
        $this->connection = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $password);
    }


    /**
     * Функция для получения товаров из базы данных
     * @param string $sort - сортировка по цене, если не передается выводится по умолчанию
     * @param string $filter - фильтрация товаров по названию, если не передается выводятся все товары
     * @return array
     */
    public function list(string $sort = 'default', string $filter = ''): array
    {
        $filter = mb_strtolower($filter);
        $sql = 'SELECT * from products ';
        $sql .= $filter ? "where name like '%$filter%' " : '';
        $sql .= $sort != 'default' ? "order by price $sort" : '';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
