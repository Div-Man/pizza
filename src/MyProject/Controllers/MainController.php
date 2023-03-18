<?php

namespace MyProject\Controllers;

use  MyProject\Views\View as View;
use  MyProject\Services\Db as Db;
use MyProject\Models\Articles\Article as Article;
use MyProject\Models\Products\Product;



use MyProject\Models\Users\UsersAuthService as UsersAuthService;

class MainController {
    
    public $view;
    public $db;
    public $user;
    
    public function __construct(View $view, Db $db)
    {
        $this->view = $view;
        $this->db = $db;
        $this->user = UsersAuthService::getUserByToken();    
   
    }
    
    
    public function main()
    {
       
        $products = new Product;
        $products = $products->findAll();
        
         $this->view->render('/main/main.php', ['products' => $products]);
    }
    
    public function addToCart($id)
    {
        $product = new Product;
        return $product->getProductById($id);
        
    }
    
    public function openCart($ids)
    {
    
         $product = new Product;
         return $product->getAllProductsCart($ids);      
    }
    
    public function payOrder()
    {   
         $data = file_get_contents('php://input');
         $payQiwi = new Product;
        $result = $payQiwi->pay($data);
        return $result;
           
    }
    
    public function payCheck($billId)
    {
        $order = new Product;
        $result = $order->orderCheck($billId);
        
        if($result){
           setcookie("orderId", $billId, time() + 3600, '/');
            header('Location: /users/profile');
            exit;
        }
        else {
             $this->view->render('/main/error.php', []);
        }
       
    }
    
 
}