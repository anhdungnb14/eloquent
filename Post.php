<?php
require_once __DIR__ . '/Model.php';

class Post extends Model
{
    protected $table = 'post';
    private $name = null;
    private $price = null;
    private $fillable = [
        'name',
        'price'
    ];

    public function getFilAble()
    {
        return $this->fillable;
    }

    /**
     * @param null $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return null
     */
    public function getPrice()
    {
        return $this->price;
    }
}
