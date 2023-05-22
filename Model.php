<?php
//require_once __DIR__ . '/Comments.php';

class Model
{
    protected $table;
    private $pdo;
    private $where = [];
    private $select = ' * ';
    private $groupBy = null;
    private $orderBy = null;
    private $having = null;
    private $limit = null;
    private $offset = null;
    private $join = [];
    protected $attribute = [];
    protected $primaryKey = 'id';
    private $oneToMany = [];

    public function __set($name, $value)
    {
        $this->attribute[$name] = $value;
    }

    public function __get($name)
    {
        return $this->$name();
    }


    public function __construct()
    {
        $this->pdo = $this->connectDatabase();
    }

    /**
     * @return false|PDO
     */
    public function connectDatabase(): bool|PDO
    {
        try {
            return new PDO("mysql:host=localhost;dbname=laravel_training", 'root', 'mysql');
        } catch (PDOException $e) {
            echo "Kết nối thất bại: " . $e->getMessage();
        }
        return false;
    }

    /**
     * @param $data
     * @return bool|int
     */
    public function insert($data): bool|int
    {
        $columnKey = array_keys($data);
        $columns = implode(', ', $columnKey);
        $placeHolderSql = implode(', ', array_map(function ($item) {
            return ":$item";
        }, $columnKey));
        $sql = "INSERT INTO $this->table ($columns) VALUES ($placeHolderSql)";
        $dataExcute = $this->pdo->prepare($sql)->execute($data);
        if ($dataExcute) {
            $id = $this->pdo->lastInsertId();
        }
        return $id ?? false;
    }

    /**
     * @return bool|int
     */
    public function save(): bool|int
    {
        $id = $this->insert($this->attribute);
        return $id ?? false;
    }

    /**
     * @param $data
     * @return bool
     */
    public function update($data): bool
    {

        list($where, $dataWhere) = $this->whereAnd();
        $dataExcute = array_merge($data, $dataWhere);
        $columns = array_keys($data);
        $placeHolderSql = implode(', ', array_map(function ($item) {
            return "$item=:$item";
        }, $columns));
        $sql = "UPDATE {$this->table} SET {$placeHolderSql}";
        if ($this->where) {
            $sql .= " WHERE $where";
        }
//        echo $sql;
//        die;
        $id = $this->pdo->prepare($sql)->execute($dataExcute);
        return $id ? true : false;
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        $dataWhere = [];
        $sql = "DELETE FROM {$this->table}";
        if ($this->where) {
            list($where, $dataWhere) = $this->whereAnd();
            $sql .= " WHERE $where";
        }
        $id = $this->pdo->prepare($sql)->execute($dataWhere);
        return $id ? true : false;

    }

    /**
     * @return $this
     */
    public function where(): static
    {
        $numArg = func_num_args();
        $args = func_get_args();
        if ($numArg === 2) {
            $column = $args[0];
            $operator = '=';
            $value = $args[1];
        } elseif ($numArg === 3) {
            $column = $args[0];
            $operator = $args[1];
            $value = $args[2];
        } else {
            $column = null;
            $operator = null;
            $value = null;
        }
        $this->where[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
        ];
        return $this;
    }

    public function whereArray($conditionArray)
    {
        if (is_array($conditionArray) && count($conditionArray) == 1) {
            $conditionArray = [$conditionArray];
        }
        foreach ($conditionArray as $itemArray) {
            list($column, $operator, $value) = $itemArray;
            $this->where[] = [
                'column' => $column,
                'operator' => $operator,
                'value' => $value,
            ];

        }
        return $this;
    }

    /**
     * @return array
     */
    private function whereAnd(): array
    {

        $where = array_map(function ($value, $key) use (&$dataWhere) {
            $paramName = 'where_' . $key;
            $dataWhere[$paramName] = $value['value'];
            return $value['column'] . ' ' . $value['operator'] . ' :' . $paramName;
        }, $this->where, array_keys($this->where));
        $where = implode(' AND ', $where);
        //su dung for
//        $where = [];
//        $dataWhere = [];
//        foreach ($this->where as $key => $value) {
//            $dataWhere['where_' . $key] = $value['value'];
//            $where[] = $value['column'] . $value['operator'] . ":where_" . $key;
//        }
//        $where = implode(' AND ', $where);

        return [$where, $dataWhere];
    }


    /**
     * @param $select
     * @return $this
     */
    public function select($select): static
    {
        if ($select) {
            $this->select = $select;
        } else {
            $this->select = "*";
        }
        return $this;
    }

    /**
     * @param $groupBy
     * @return $this
     */
    public function groupBy($groupBy): static
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    /**
     * @param $having
     * @return $this
     */
    public function having($having): static
    {
        $this->having = $having;
        return $this;
    }

    /**
     * @param $orderBy
     * @return $this
     */
    public function orderBy($orderBy): static
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @param $limit
     * @param $offset
     * @return $this
     */
    public function limit($limit, $offset): static
    {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param $tableJoin
     * @param $condition
     * @return $this
     */
    public function join($tableJoin, $condition): static
    {
        $this->join[] = [
            'type' => 'INNER',
            'tableJoin' => $tableJoin,
            'condition' => $condition,
        ];
        return $this;
    }


    /**
     * @return bool|array|null
     */
    public function get(): bool|array|null
    {
        $dataWhere = [];
        if ($this->where) {
            list($where, $dataWhere) = $this->whereAnd();
        }
        $sql = $this->setSql();

        //limit
        if ($this->limit) {
            if ($this->offset) {
                $sql .= " LIMIT " . $this->offset . ', ' . $this->limit;
            } else {
                $sql .= " LIMIT " . $this->limit;
            }
        }
        $datas = $this->fetchAllData($sql, $dataWhere);
        if ($this->oneToMany) {
            foreach ($this->oneToMany as $oneToManyItem) {
                $oneToMany = new OneToMany();
                $dataRelation[$oneToManyItem['tableReltion']] = $oneToMany->oneToMany($datas, $oneToManyItem, $this->pdo, $this->primaryKey);
            }
            $normalize = new NormalizeData();
            $datas = $normalize->NormalizeData($datas, $dataRelation, $this->oneToMany);
        }
        return $datas ?? null;
    }

    /**
     * @return bool|array|null
     */
    public function first(): bool|array|null
    {
        $dataWhere = [];
        if ($this->where) {
            list($where, $dataWhere) = $this->whereAnd();
        }
        $sql = $this->setSql();
        $sql .= " LIMIT 1";
//        var_dump($dataWhere);
//        echo $sql;
//        die;
        $datas = $this->fetchAllData($sql, $dataWhere);
        return $datas ?? null;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE id=:where_0";
        $dataWhere = ['where_0' => $id];
        $data = $this->fetchData($sql, $dataWhere);
        return $data ?? null;
    }

    /**
     * @return string
     */
    public function setSql(): string
    {
        $sql = "SELECT ";

        //select
        if ($this->select) {
            $sql .= "{$this->select} FROM {$this->table}";
        }

        //join
        if ($this->join) {
            foreach ($this->join as $value) {
                $sql .= " " . $value['type'] . " JOIN " . $value['tableJoin'] . " ON " . $value['condition'];
            }
        }

        //where
        if ($this->where) {
            list($where, $dataWhere) = $this->whereAnd();
            $sql .= " WHERE " . $where;
        }

        //group by
        if ($this->groupBy) {
            $sql .= " GROUP BY " . $this->groupBy;
        }

        //having
        if ($this->having) {
            $sql .= " HAVING " . $this->having;
        }

        //order by
        if ($this->orderBy) {
            $sql .= " ORDER BY " . $this->orderBy;
        }
        return $sql;
    }

    /**
     * @param $className
     * @param $foreignKey
     * @return $this
     */
    public function hasMany($className, $foreignKey): static
    {
        $classInstance = new $className();
        $tableReltion = $classInstance->table;
        $this->oneToMany[] = [
            'tableReltion' => $tableReltion,
            'foreignKey' => $foreignKey,
        ];
        return $this;
    }

    /**
     * @param $modelRelation
     * @return $this
     */
    public function with($modelRelation): static
    {
        if (!is_array($modelRelation)) {
            $modelRelation = func_get_args();
        }
        foreach ($modelRelation as $model) {
            $this->$model();
        }
        return $this;
    }

    public function oneToMany()
    {
        $sql = "SELECT * FROM categories";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_OBJ);
        $idCategories = [];
        foreach ($categories as $category) {
            $idCategories[] = $category->id;
        }
        $idCategories = implode(', ', $idCategories);
        $sql = "SELECT * FROM products WHERE category_id IN ({$idCategories})";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);
        $productsByCategory = [];
        foreach ($products as $product) {
            $productsByCategory[$product->category_id][] = $product;
        }
        foreach ($categories as $category) {
            $category->products = $productsByCategory[$category->id];
        }
        return $this;
    }

    public function belongsTo()
    {
        $sql = "SELECT * FROM products";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);
        $idCategories = [];
        foreach ($products as $product) {
            if (!in_array($product->category_id, $idCategories)) {
                $idCategories[] = $product->category_id;
            }
        }
        $idCategories = implode(', ', $idCategories);
        $sql = "SELECT * FROM categories WHERE id IN ({$idCategories})";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_OBJ);
        $categoriesNew = [];
        foreach ($categories as $category) {
            $categoriesNew[$category->id][] = $category;
        }
        foreach ($products as $product) {
            $product->categories = $categoriesNew[$product->category_id];
        }
        echo '<pre>';
        print_r($products);
    }

    public function manyToMany()
    {
        $sql = "SELECT * FROM products";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);
        $idProducts = [];
        foreach ($products as $product) {
            $idProducts[] = $product->id;
        }
        $idProducts = implode(', ', $idProducts);
        $sql = "SELECT a.*, b.product_id, b.tag_id FROM tags as a INNER JOIN product_tag as b ON a.id = b.tag_id WHERE b.product_id IN ({$idProducts})";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $tags = $stmt->fetchAll(PDO::FETCH_OBJ);
        $tagsByProductId = [];
        foreach ($tags as $tag) {
            $tagsByProductId[$tag->product_id][] = $tag;
        }
        foreach ($products as $product) {
            $product->tags = $tagsByProductId[$product->id] ?? [];
        }

        echo '<pre>';
        print_r($products);
        die;
    }

    /**
     * @param $sql
     * @param $dataWhere
     * @return bool|array|null
     */
    public function fetchAllData($sql, $dataWhere): bool|array|null
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($dataWhere);
        $datas = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $datas ?? NULL;
    }

    /**
     * @param $sql
     * @param $dataWhere
     * @return bool|array|null
     */
    public function fetchData($sql, $dataWhere): bool|array|null
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($dataWhere);
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        return $data ?? NULL;
    }

}
