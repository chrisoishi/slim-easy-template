<?php

namespace App\Modules\ApiSupport;

use DateTime;
use Exception;

class Model
{
    const table = "";
    const pk = "id";
    const timestamp_manage = true;
    const casts = ["id" => "integer"];
    const protected = [];

    protected $db;
    protected $data = [];


    public function __construct($rawData = null)
    {
        $this->db = Database::getInstance();
        if ($rawData != null) $this->setData($rawData);
    }


    public function set($field, $value)
    {
        $this->data[$field] = $value;
    }
    public function get($field)
    {
        return $this->data[$field];
    }

    public function setData($rawData)
    {
        $filedsNames = $this->getFieldsNames();
        foreach ($filedsNames as $field) {
            if (isset($rawData[$field])) {
                if (isset($this::casts[$field])) $this->data[$field] = $this->cast($rawData[$field], $this::casts[$field]);
                else $this->data[$field] = $rawData[$field];
            } else $this->data[$field] = null;
        }
        return $this;
    }
    public function save($forceNew = false)
    {
        $this->updateTimestamp();
        if ($this->isNew() || $forceNew) {
            $this->db->insert($this::table, $this->data);
            if($this->getUnique()==null)$this->data[$this::pk] = $this->db->id();
        } else {
            $this->db->update($this::table, $this->data, [
                $this::pk => $this->getUnique()
            ]);
        }
        $this->checkErrorsDatabase();
    }

    public function delete()
    {
        if (!$this->isNew()) {

            $this->db->delete($this::table, [
                $this::pk => $this->getUnique()
            ]);
        }
    }

    public function test()
    {
        return $this;
    }


    public  function updateTimestamp()
    {
        if ($this::timestamp_manage) {
            $dt = (new DateTime())->format("Y-m-d H:i:s");
            if ($this->data["created_at"] == null) {
                $this->data["created_at"] = $dt;
            }
            $this->data["updated_at"] = $dt;
        }
    }

    public function getUnique()
    {
        return $this->data[$this::pk] ?? null;
    }
    public function isNew()
    {
        return $this->getUnique() == null ? true : false;
    }

    public function toJSON()
    {

        return json_encode($this->getData());
    }

    public function getData()
    {
        $data = [];
        foreach ($this->data as $field => $value) {
            if (!in_array($field, $this::protected)) $data[$field] = $value;
        }
        return $data;
    }

    public function getFields()
    {
        return $this->db->query("SHOW COLUMNS FROM " . $this::table)->fetchAll();
    }

    public function getFieldsNames()
    {
        $fields = $this->getFields();
        $names = [];
        foreach ($fields as $f) {
            array_push($names, $f["Field"]);
        }
        return $names;
    }

    public function cast($value, $type)
    {
        switch ($type) {
            case "integer":
                return intval($value);
        }
    }

    public function checkErrorsDatabase()
    {
        $errors = $this->db->error();
        // echo json_encode( $errors);
        if (count($errors) > 3) throw new Exception("errors database");
    }


    //########################
    // STATIC FUNCTIONS
    //########################

    /**
     * @return $this
     */
    public static function find($unique)
    {
        $class = get_called_class();
        $db = Database::getInstance();
        $raw = $db->select($class::table, "*", [
            $class::pk => $unique
        ]);
        if (count($raw) > 0) return (new $class())->setData($raw[0]);
        return null;
    }

    /**
     * @return Collection<$this>
     */
    public static function list($select = "*", $where = []): Collection
    {
        $class = get_called_class();
        $collection = new Collection($class);
        $db = Database::getInstance();
        $raw = $db->select($class::table, $select, $where);
        foreach ($raw as $raw_item) {
            $collection->add((new $class())->setData($raw_item));
        }
        return $collection;
    }
}
