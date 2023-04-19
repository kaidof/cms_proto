<?php

declare(strict_types=1);

namespace core\models;

use core\Collection;

abstract class BaseModel
{
    /**
     * Database table
     *
     * @var string
     */
    protected $table;

    /**
     * Record id
     *
     * @var int
     */
    protected $id;

    /**
     * Record data
     *
     * @var array
     */
    protected $data = [];

    /**
     * Record original data
     *
     * @var array
     */
    protected $originalData = [];

    /**
     * Fields that will be ignored when converting to array
     *
     * @var array
     */
    protected $hidden = [];

    public function __construct($id = null)
    {
        if ($id) {
            $this->setId($id);
            $this->loadById($id);
        }
    }

    /**
     * Set the id of the model
     *
     * @param $id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Return the id of the model
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function setData($data)
    {
        // remove id from data array
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $this->data = $data;

        // Store the original data when setting the data for the first time
        if (empty($this->originalData)) {
            $this->originalData = $data;
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function __get($key)
    {
        if ($key == 'id') {
            return $this->getId();
        }

        return $this->data[$key] ?? null;
    }

    public function __set($key, $value)
    {
        $this->data[$key] = $value;

        // change id
        if ($key == 'id') {
            $this->setId($value);
        }
    }

    /**
     * Check if the model data has been changed
     *
     * @return bool
     */
    public function isDataChanged()
    {
        return $this->data !== $this->originalData;
    }

    /**
     * Update a record in the database
     *
     * @return void
     */
    public function update()
    {
        // Check if the data has been changed
        if ($this->isDataChanged()) {
            // Implementation to update the record data in the database

            // remove id from data array
            if (isset($this->data['id'])) {
                unset($this->data['id']);
            }

            db()->update($this->table, $this->data, "id = {$this->id}");

            // Update the original data to reflect the current data
            $this->originalData = $this->data;
        } else {
            // No changes, do nothing
        }
    }

    /**
     * Save the model
     *
     * @return void
     */
    public function save()
    {
        if ($this->id) {
            $this->update();
        } else {
            $this->saveNew();
        }
    }

    /**
     * Create a new record in the database
     *
     * @return void
     */
    protected function saveNew()
    {
        // remove id from data array
        if (isset($this->data['id'])) {
            unset($this->data['id']);
        }

        $id = db()->insert($this->table, $this->data);

        $this->id = (int)$id;

        // Update the original data to reflect the current data
        $this->originalData = $this->data;
    }

    /**
     * Create a new model and save it to the database
     *
     * @param array $data
     * @return static
     */
    public static function create(array $data)
    {
        $model = new static();

        $model->setData($data);
        $model->saveNew();

        return $model;
    }

    /**
     * Load model data by id
     *
     * @param $id
     * @return void
     */
    public function loadById($id)
    {
        // Implementation to fetch a record from the database by ID
        $data = db()->queryOne('SELECT * FROM ' . $this->table . ' WHERE id = :id', ['id' => $id]);
        // var_dump($data);

        if ($data) {
            $this->setData($data);
            $this->setId($id);
        }
    }

    /**
     * Load model data by id
     *
     * @param int $id
     *
     * @return static|null
     */
    public static function find($id)
    {
        $model = new static();
        $model->loadById($id);

        return $model->id ? $model : null;
    }

    /**
     * Load model data by id or throw an exception if the model is not found
     *
     * @param int $id
     * @return static
     * @throws \Exception
     */
    public static function findOrFail($id)
    {
        $model = new static();
        $model->loadById($id);

        if (!$model->getId()) {
            throw new \Exception('Model not found');
        }

        return $model;
    }

    /**
     * Loads all records from the database into a collection
     *
     * @return Collection<static>
     */
    public static function all()
    {
        $model = new static();
        $data = db()->query('SELECT * FROM ' . $model->table);

        $list = [];
        foreach ($data as $item) {
            $model = new static();
            $model->setData($item);
            $model->setId($item['id']);
            $list[] = $model;
        }

        return new Collection($list);
    }

    /**
     * Delete the model from the database
     *
     * @return void
     */
    public function delete()
    {
        db()->deleteById($this->table, $this->id);
    }

    /**
     * Convert the model to an array
     * Fields that are in the hidden array will be ignored
     *
     * @return array
     */
    public function toArray()
    {
        $fields = ['id' => $this->id, ...$this->data];

        // filter fields that will be not ignored
        return array_filter($fields, function ($key) {
            return !in_array($key, $this->hidden);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Convert the model to JSON
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
}
