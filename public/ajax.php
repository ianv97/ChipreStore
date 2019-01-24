<?php
session_start();

function find_product($id) {
    $product = DB::table('products')
            ->leftJoin('product_photos','product_photos.product_id', '=', 'products.id')
            ->leftJoin('categories_products','categories_products.product_id', '=', 'products.id')
            ->leftJoin('categories','categories_products.category_id', '=', 'category.id')
            ->where('products.id', $id)->first();
    return $product;
};

switch($_POST["action"]){
	case 1: echo json_encode(find_product($_POST['product_id'])); break;
}
?>