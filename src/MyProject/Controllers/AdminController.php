<?php

namespace MyProject\Controllers;

use MyProject\Views\View as View;
use MyProject\Models\Admin\Main as Main;
use MyProject\Models\Users\UsersAuthService;

class AdminController {

    public $view;
    public $user;
    public $main;

    public function __construct(View $view) {
        $this->view = $view;
        $this->user = UsersAuthService::getUserByToken();
        $this->main = new Main();
        $this->denyAccess($this->checkAdminUser());
    }

    public function checkAdminUser() {
        

        if (!isset($this->user) || $this->user->user_role !== 'admin') {
            return false;
        }
        return true;
    }
    
    public function denyAccess($user)
    {
        if(!$user){
            $this->view->render('/errors/404.php', [], 404);
            exit;
        }
            
    }
    
    public function index() {
        
        $orders = $this->main->getAllOrders();
        $mode = $this->main->getStatusTestMode();
        $this->view->render('/admin/index.php', ['mode' => $mode[0], 'infoOrder' => $orders]);
          
    }

    public function updateStatusModePay() {
        $this->main->updateStatusPay($_POST);
        header('Location: /admin');
    }

}
