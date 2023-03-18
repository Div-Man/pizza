<?php include __DIR__ . '/../header.php'; ?>

<header>
    <div class="box">
        <div class="logo"><a class="go-main-page" href="/"><img src="/img/pngwing.com.png" alt="Girl in a jacket" width="100" height="100"></a></div>
        <ul>
            <li><a href="/#rolls" >Роллы</a></li>
            <li><a href="/#pizza" >Пицца</a></li>
            <li><a href="/#snacks">Закуски</a></li>
            <li><a href="/#paste">Паста</a></li>
            <li><a href="/#drinkables">Напитки</a></li>

            <?php if(empty($user)) echo '<li class="toggle-profile"><a class="openLoginForm" onclick="loginOpenForm(event);" href="">Войти</a></li>';?>
            
            <?php if(!empty($user)) echo '<li><a class="" href="/users/profile">Профиль</a></li>';?>


        </ul>  
        <div class="cart" style="" onclick="showCart()">
            <span>Корзина</span>
            <div class="count-product">0</div>
        </div>
    </div>

</header>


<div class="wrapLoginForm">
    <div class="form-success"></div>;


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

            <div class="loginLosePWD">
                <span class="">Забыли пароль ?</span>
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

            <div class="loginLosePWD">
                <span class="">Надежный пароль - строчные и заглавные буквы, цифры и символы.
                    Например: #?@rS49V9b</span>
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
            <div ><input class="MuiButton-root" type="submit" value="Оформить заказ"></div>
        </form>

    </div>
</section>

<div class="wrap">
    
<div class="order-success profile-order">


    <h2>Страница не найдена</h2>



</div>
</div>



<?php include __DIR__ . '/../footer.php'; ?>

