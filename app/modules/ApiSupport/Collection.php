<?php

namespace App\Modules\ApiSupport;

class Collection
{
    protected $items = [];
    protected $class;
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function toJSON()
    {
        $data = [];
        foreach ($this->items as $item) {
            array_push($data, $item->getData());
        }
        return json_encode($data);
    }

    public function add($model)
    {
        array_push($this->items, $model);
    }

    /**
     * @return T
     */
    public function get($index)
    {
        return $this->items[$index];
    }

    public function getAll()
    {
        return $this->items;
    }

    public function removeAt($index)
    {
        unset($this->items[$index]);
    }
    public function remove($model)
    {
        $i = 0;
        foreach ($this->items as $item) {
            if ($item == $model) break;
            $i++;
        }
        $this->removeAt($i);
    }

    public function count()
    {
        return count($this->items);
    }
    public function isEmpty()
    {
        return $this->count() > 0 ? false : true;
    }
    public function first(){
        return $this->items[0];
    }
}
