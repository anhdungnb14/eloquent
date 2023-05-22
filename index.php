<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Product.php';
require_once __DIR__ . '/Category.php';
require_once __DIR__ . '/OneToMany.php';
require_once __DIR__ . '/NormalizeData.php';
require_once __DIR__ . '/Brand.php';

$productModel = new Product();

// them moi san pham
$productModel->name = 'prd 1';
$productModel->price = 200;
$id = $productModel->save();

// them moi san pham
$id = $productModel->insert([
    'name' => 'sp 1',
    'price' => '100000',
    'description' => 'test',
]);

//update san pham
$productModel
    ->where('id', 4)
    ->where('name', 'sp 1')
    ->update([
        'name' => 'iphone',
        'price' => 100,
    ]);

// truy van du lieu
$productModel
    ->where('id', 4)
    ->delete();
$data = $productModel
    ->select('id, name')
//    ->join('product_tag', 'products.id = product_tag.product_id')
    ->where('id','>', 5)
    ->where('name', 'sp 1')
    ->groupBy('id')
//    ->having('price > 1000')
    ->orderBy('id')
    ->limit(2, 1)
    ->get();


// lay ban ghi dau tien
$data = $productModel
    ->select('id, name')
    ->where('id','>', 5)
    ->join('product_tag', 'products.id = product_tag.product_id')
    ->where('name', 'sp 1')
    ->groupBy('id')
    ->having('price > 1000')
    ->orderBy('id')
    ->limit(2, 1)
    ->first();


// truy van theo id
$data = $productModel
    ->select('id, name')
    ->join('product_tag', 'products.id = product_tag.product_id')
    ->whereArray([['id', '=', 5], ['name','=', 'sp 1']])
    ->groupBy('id')
//    ->having('price > 1000')
    ->orderBy('id')
    ->limit(2, 1)
    ->get();
$data = $productModel
    ->find(6);

// eager loading
//$products = new Product();
//$products->manyToMany();
$categories = new Category();
$data = $categories
    ->select('id, name')
    ->with(['products','brands'])
    ->get();
echo '<pre>';
print_r($data);
