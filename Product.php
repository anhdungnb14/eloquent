<?php

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

}
