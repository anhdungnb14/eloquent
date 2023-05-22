<?php

class Brand extends Model
{
    protected $table = 'brands';
    protected $primaryKey = 'id';

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
