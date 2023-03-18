<?php
return [
   
    
    '~^/$~' => ['\MyProject\Controllers\MainController', 'main'],
     '~^/product/add/(\d+)$~' => ['MyProject\Controllers\MainController', 'addToCart'],
     '~^/cart\/ids/(.+)$~' => ['MyProject\Controllers\MainController', 'openCart'],
     '~^/cart/pay/(.*)$~' => ['MyProject\Controllers\MainController', 'payOrder'],
    '~^/payment/check/(.*)$~' => ['MyProject\Controllers\MainController', 'payCheck'],
   
    
    
    
    
    '~^/users/register$~' => ['MyProject\Controllers\UsersController', 'register'],
    '~^/users/(\d+)/activate/(.+)$~' => ['\MyProject\Controllers\UsersController', 'activate'],
    '~^/users/login$~' => ['MyProject\Controllers\UsersController', 'login'],
    '~^/users/profile$~' => ['MyProject\Controllers\UsersController', 'profile'],
    '~^/users/logout$~' => ['MyProject\Controllers\UsersController', 'logout'],
    
     '~^/admin$~' => ['MyProject\Controllers\AdminController', 'index'],
    '~^/admin/pay/toggle$~' => ['MyProject\Controllers\AdminController', 'updateStatusModePay'],
     
    
];
