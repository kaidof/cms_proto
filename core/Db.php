<?php

declare(strict_types=1);

namespace core;

class Db
{
    private static $instance = null;

    /**
     * @var \PDO
     */
    private $db;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        try {
            $this->db = new \PDO(
                'mysql:host=' . env()->get('DB_HOST', '') . ';dbname=' . env()->get('DB_DATABASE', ''),
                env()->get('DB_USERNAME', ''),
                env()->get('DB_PASSWORD', ''),
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_EMULATE_PREPARES => true,
                    \PDO::ATTR_PERSISTENT => false,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                ]
            );

            $this->db->query('SET NAMES UTF8;');
        } catch (\Throwable $e) {
            logger()->error('database connection error: ' . $e->getMessage());
            throw new \Exception('Database connection failed: ' . $e->getMessage());
        }
    }

    /**
     * @param string $query
     * @param array $params
     * @return array|false
     */
    public function query(string $query, array $params = [])
    {
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function queryOne(string $query, array $params = [])
    {
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * @param string $table
     * @param array $values
     * @return bool|string
     */
    public function insert($table, $values)
    {
        $fields = [];
        $valList = [];
        $params = [];

        foreach ($values as $field => $value) {
            $fields[] = $field;
            $valList[] = '?';
            $params[] = $value;
        }

        $fields = implode(', ', $fields);
        $valList = implode(', ', $valList);

        $query = "INSERT INTO {$table} ({$fields}) VALUES ({$valList})";
        //var_dump($query);
        //var_dump($params);

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $this->db->lastInsertId();
    }

    public function update($table, $values, $whereSql)
    {
        $fields = [];
        $params = [];

        foreach ($values as $field => $value) {
            $fields[] = $field . ' = ?';
            $params[] = $value;
        }

        $fields = implode(', ', $fields);

        $query = "UPDATE {$table} SET {$fields} WHERE {$whereSql}";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->rowCount();
    }

    /**
     * Upsert - insert or update
     *
     * @param string $table Table name
     * @param array $values Array of field => value
     * @return int Number of affected rows
     */
    public function upsert($table, $values)
    {
        $fields = [];
        $params = [];

        foreach ($values as $field => $value) {
            $fields[] = $field . ' = ?';
            $params[] = $value;
        }

        $fields = implode(', ', $fields);

        $query = "INSERT INTO {$table} SET {$fields} ON DUPLICATE KEY UPDATE {$fields}";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->rowCount();
    }

    public function deleteById($table, $id)
    {
        $query = "DELETE FROM {$table} WHERE id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);

        return $stmt->rowCount();
    }

}
