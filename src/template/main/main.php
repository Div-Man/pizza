<?php include __DIR__ . '/../header.php'; ?>

<header>
    <div class="box">
       <div class="logo"><a class="go-main-page" href="/"><img src="/img/pngwing.com.png" alt="Girl in a jacket" width="100" height="100"></a></div>
        <ul>
            <li><a href="#rolls" >Роллы</a></li>
            <li><a href="#pizza" >Пицца</a></li>
            <li><a href="#snacks">Закуски</a></li>
            <li><a href="#paste">Паста</a></li>
            <li><a href="#drinkables">Напитки</a></li>
            
         
            
            <?php if(empty($user)) echo '<li class="toggle-profile"><a class="openLoginForm" onclick="loginOpenForm(event);" href="">Войти</a></li>';?>
            
            <?php if(!empty($user)) echo '<li><a class="" href="/users/profile">Профиль</a></li>';?>
            
            
        </ul>  
        <div class="cart" style="" onclick="showCart()">
            <span>Корзина</span>
            <div class="count-product">0</div>
        </div>
    </div>

</header>

<div class="wrapOrderNoLogin ">
    <div class="form-success2"></div>
</div>

<div class="wrapLoginForm">
    <div class="form-success"></div>
    

    <div class="form-login">
        <form action="/users/login" name="login" method="POST">
        <div class="loginIMG">
            <img alt="Login" title="Login" src="img/account-icon-240x240.png">
        </div>
        <div class="loginHeader">
            <h2 class="">Мой аккаунт</h2>
        </div>
        <div class="error-message error-email"></div>
        <div class="inputLogin css-i44wyl">
            <div class="css-1v4ccyo">
                 <input type="email" class="email" placeholder="Email"  name="email" value="" >

            </div>
        </div>
        <div class="error-message error-password"></div>
        <div class="inputLogin css-i44wyl">
            <div class="css-1v4ccyo">
                <input type="password" placeholder="Пароль"  name="password" class="" value="" >

            </div>
        </div>

        <div class="loginLogin userLogin">
           
             <input class="css-9l3uo3" type="submit" value="Войти" />
        </div>

        <div class="loginCreate create-new-account" onclick="createOccount(this)">
            <span class="css-9l3uo3">Создать новый аккаунт</span>
        </div>
    </form>
    </div>
    
    
    
     <div class="register-form" style="display: none;">
         <form action="/users/register" name="register" method="POST">
        <div class="loginIMG">
            <img alt="Login" title="Login" src="img/account-icon-240x240.png">
        </div>
        <div class="loginHeader">
            <h2 class="">Новый аккаунт</h2>
        </div>
        <div class="error-message error-email"></div>
        <div class="inputLogin css-i44wyl">
            <div class="css-1v4ccyo">
                 <input type="email" class="email" placeholder="Email"  name="email" value="" >

            </div>
        </div>
        <div class="error-message error-password"></div>
        <div class="inputLogin css-i44wyl">
            <div class="css-1v4ccyo">
                <input type="password" placeholder="Пароль"  name="password" value="" >

            </div>
        </div>
             
        <div class="loginLogin userRegister">
           
            <input class="css-9l3uo3" type="submit" value="Создать аккаунт" />
        </div>

        <div class="loginCreate go-login" onclick="goLogin(this)">
            <span class="css-9l3uo3">У меня есть аккаунт</span>
        </div>
</form>
    </div>
</div>

<section class="open-cart" style="display: none">
    <div class="cart-croll">
        <table class="TableMini">
            <tbody class="dynamic-layout-js">


            </tbody>


        </table>
    </div>


   
     <div class="tfoot">
                <div class="tr">
                   <div class="td">Итого:</div>
                    <div class="td">
                        <div class="total-price"></div>
                    </div>
                </div>
            </div>
      
    
    <div class="InCart">
        
        
        
        <form action="/cart/pay/" method="post">
            <input type="hidden" class="cart-pay" name="products" value="">
            <div>
                <input class="MuiButton-root" onclick="btnBuy(event)" type="submit" value="Купить">
            </div>
           </form>

    </div>
</section>

<?php

$newArr = [];

foreach ($products as $val) {
    $newArr[$val->category_id][] = $val;
}
?>
<div class="wrap">





    <?php

    function renderHTML($value, $magnitude) {
        return '<span>' . $value . ' ' . $magnitude . '</span>';
    }
    ?>


    <?php
    $categoryAlias = [
        1 => 'rolls',
        2 => 'pizza',
        3 => 'snacks',
        4 => 'paste',
        5 => 'drinkables',
        6 => 'sauces',
    ];
    ?>


    <?php foreach ($newArr as $category => $productArray): ?>
        <div class="content" id="<?php echo $categoryAlias[$category] ?>">

            <?php foreach ($productArray as $product): ?>
                <div class="block">
                    <img src="img/<?php echo $product->img_path; ?>" alt="Girl in a jacket" width="300" height="300">
                    <div class="title"><?php echo $product->title; ?></div>
                    <div class="properties-wrap">
                        <div class="properties">
                            <?php
                            if (!empty($product->section_number))
                                echo renderHTML($product->section_number, 'ролла');
                            if (!empty($product->diameter))
                                echo renderHTML($product->diameter, 'см');
                            if (!empty($product->total))
                                echo renderHTML($product->total, 'шт');
                            if (!empty($product->weight))
                                echo renderHTML($product->weight, 'г');
                            if (!empty($product->volume))
                                echo renderHTML($product->volume, 'л');
                            ?>
                        </div>
                    </div>
                    <div class="description">
                        <?php echo $product->description; ?>
                    </div>

                    <div class="buy-block" id="block<?php echo $product->id; ?>">

                        <script>
                            if (!showCountAddedToCart(<?php echo $product->id; ?>)) {
                                createToCart(<?php echo $product->id; ?>, <?php echo $product->price; ?>);
                            } else {
                                cartIncr(<?php echo $product->price; ?>, <?php echo $product->id; ?>);
                            }
                        </script>
                    </div>
                </div>


            <?php endforeach; ?>

        </div>
        <?php
        ?>

    <?php endforeach; ?>

</div>
<?php include __DIR__ . '/../footer.php'; ?>

