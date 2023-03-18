<footer style="position: fixed; bottom: 0;">
    <span>Pet-проект 2023</span>
</footer>





<script>

    function btnBuy(e) {
        e.preventDefault();
        let btn = document.querySelector('.MuiButton-root');
        let form = e.target.closest('form');
        let action = form.getAttribute("action");
        let data = form.querySelector('.cart-pay').getAttribute("value");
        let formSuccess2 = document.querySelector('.form-success2');
        let cart = document.querySelector('.open-cart');

       
        try {

            fetch(action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: data
            })
                    .then(response => response.json())
                    .then(result => {

                        if (result['error'] == false || result['error'] == 'empty') {
                            let wrap = document.querySelector('.wrapOrderNoLogin');
                            wrap.style.display = 'flex';
                            cart.style.display = 'none';
                            formSuccess2.style.display = 'flex';
                            let message = result['error'] == false ? "Для оплаты, вам нужно войти в профиль." : result['error'] == 'empty' ? "Корзина пуста." : "";
                            
                            formSuccess2.innerHTML = message;

                            wrap.addEventListener('click', function (event) {
                                wrap.classList.add('fade-out');
                                setTimeout(function () { //без setTimeout браузер не упееет перерисовать элемент
                                    wrap.style.display = 'none';
                                    wrap.classList.remove('fade-out');
                                }, 500);

                            });

                        }
                        if(result['success'] == true){
                            localStorage.setItem('my_cart', '[]');
                            location.href = '/payment/check/' + result['order_id'];
                        }
                        
                        if(result['link']){
                            localStorage.setItem('my_cart', '[]');
                            location.href = result['link'];
                        }


                    });
        } catch (error) {
            alert('Произошла ошибка при оплате');
        }



    }

    ///////////////////////////////////////////////

    function createJsonCart()
    {
        let myCart = JSON.parse(window.localStorage.getItem('my_cart'));
        let data = {};

        myCart.forEach(function (elem) {
            data[elem['item_id']] = elem['count'];
        });

        data = JSON.stringify(data);

        return data;

    }

    var pay = document.querySelector('.cart-pay'); //много где дублируется 
    pay.value = createJsonCart();

    function deleteClass()
    {
        for (var item of document.querySelectorAll('a[href^="#"]')) {
            item.classList.remove('active');
        }
    }

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {

            deleteClass();
            e.preventDefault();
            e.target.classList.add("active");
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
    ////////////////////////////////////////////////////////

    showCountCart();

    function findTwinItem(e, id, totalPrice, newCount, price) {
        var pay = document.querySelector('.cart-pay'); //много где дублируется 
        pay.value = createJsonCart();

        let wrapElement = e.parentElement;
        if (wrapElement.classList.contains('plusminus')) {

            let totalCartPrice = document.querySelector('.total-price');

            let cartBlock = document.getElementById('cart-block' + id);

            if (cartBlock) {
                let allPricecartBlock = cartBlock.querySelector('.all-price');
                allPricecartBlock.innerHTML = totalPrice + ' Р';
                totalCartPrice.innerHTML = getTotalPrice() + ' Р';

            }


            let block = document.getElementById('block' + id);

            let result = block.querySelector('.result');
            let itemProduct = block.querySelector('.buy2');
            if (newCount == 0) {
                itemProduct.remove();
                createToCart(id, price);
                totalCartPrice.innerHTML = getTotalPrice() + ' Р';
            } else {
                result.innerHTML = newCount + " шт. на " + totalPrice + " Р";

            }


        }

        if (wrapElement.classList.contains('forCartBlock')) {



            let block = document.getElementById('cart-block' + id); //надо доделать, если в корзинге этого нету, то будеш ошибка
            if (block) {
                let result = block.querySelector('.result');
                if (newCount == 0) {
                    block.remove();
                }
                result.innerHTML = newCount;

            }

        }

    }

    function showCountCart() {

        let cart = document.querySelector('.count-product');
        let myCart = JSON.parse(window.localStorage.getItem('my_cart'));
        let allCountProduct = 0;
        myCart.forEach(function (elem, index, arr) {
            allCountProduct = allCountProduct + elem['count'];
        });
        cart.innerHTML = allCountProduct;
    }

    function getTotalPrice() {
        let myCart = JSON.parse(window.localStorage.getItem('my_cart'));
        let total = 0;

        myCart.forEach(function (elem, index, arr) {
            total = total + elem['all_price'];
        });

        return total;

    }


    function addToCart(productId, productPrice, myCart) {
        if (!myCart) {
            myCart = [];
        }

        let entry = myCart.find(({item_id}) => item_id == productId);
        if (!entry) {
            myCart.push({
                item_id: productId,
                count: 1,
                one_price: productPrice,
                all_price: productPrice
            });
            window.localStorage.setItem('my_cart', JSON.stringify(myCart));

        }
    }



    async function toCart(e) {
        let myCart = JSON.parse(window.localStorage.getItem('my_cart'));
        let id = e.closest(".buy").querySelector('.id_product').value;
        let totalPrice = document.querySelector('.total-price');

        try {
            const response = await fetch(`/product/add/${id}`);
            const product = await response.json();
            let productPrice = product.price;
            let productId = product.id;
            let parentElement = e.closest(".buy-block");
            parentElement.querySelector(".buy").remove();
            cartIncr(productPrice, productId);


            addToCart(productId, productPrice, myCart);

            totalPrice.innerHTML = getTotalPrice() + ' Р';
            showCountCart();
            var pay = document.querySelector('.cart-pay'); //много где дублируется 
            pay.value = createJsonCart();
        } catch (error) {
            alert('Произошла ошибка при добавлении товара в корзину');
        }
    }


    function removeItemCart(myCart, id) {
        let entry = myCart.findIndex(({item_id}) => item_id == id);
        myCart.splice(entry, 1);
        window.localStorage.setItem('my_cart', JSON.stringify(myCart));
    }

    function signChange(sign, entry, myCart, id, e, res, price) {
        let newCount = sign === 'plus' ? entry['count'] + 1 : entry['count'] - 1;
        entry['count'] = newCount;
        window.localStorage.setItem('my_cart', JSON.stringify(myCart));
        fetch('/product/add/' + id)
                .then(response => response.json())
                .then(product => {
                    var productPrice = product['price'];
                    var productId = product['id'];
                    var totalPrice = productPrice * newCount;
                    entry['all_price'] = totalPrice //это от плохих рук, что бы при изменении в Local Storage цена правильно отображалась 
                    window.localStorage.setItem('my_cart', JSON.stringify(myCart));
                    function renderPriceCount() {
                        if (e.parentElement.classList.contains('buy2')) {
                            res.innerHTML = newCount + " шт. на " + totalPrice + " Р";
                        }

                        if (e.parentElement.classList.contains('plusminus')) {
                            res.innerHTML = newCount;
                        }
                    }

                    if (sign == 'plus') {
                        renderPriceCount();
                    }

                    if (sign == 'minus') {
                        if (entry['count'] == 0) {
                            if (e.closest('.product-wrap-item')) {
                                e.closest('.product-wrap-item').remove();
                                removeItemCart(myCart, productId);

                            } else {
                                e.closest('.buy2').remove();
                                removeItemCart(myCart, productId);
                                createToCart(productId, productPrice);
                            }

                        } else {
                            renderPriceCount();
                        }

                    }

                    findTwinItem(e, id, totalPrice, newCount, productPrice);
                });
    }



    function quantityChange(e, sign) {
        let totalPrice = document.querySelector('.total-price');
        let parentElement = e.closest(".buy2");
        let res = parentElement.querySelector('.result');


        let price = parentElement.querySelector('input[name="priceHidden"]').value;


        let id = parentElement.querySelector('.id_product').value;
        let myCart = JSON.parse(window.localStorage.getItem('my_cart'));
        let entry = myCart.find(({item_id}) => item_id == id);
        signChange(sign, entry, myCart, id, e, res, price);
        totalPrice.innerHTML = getTotalPrice() + ' Р';
        showCountCart();
    }



    function closeCart() {
        window.addEventListener('click', function (event) {
            var cart = document.querySelector('.open-cart');
            var body = document.querySelector('.body');

            if (!event.target.closest('.open-cart') && !event.target.closest('.cart')) {
                cart.style.display = 'none';
                //enableScroll();
            }
        });
    }


    function showCart() {

        let totalPrice = document.querySelector('.total-price');
        var cart = document.querySelector('.open-cart');
        var parent = document.querySelector('.dynamic-layout-js');

        if (cart.style.display === 'none') {
            cart.style.display = "block";
            parent.innerHTML = '';
            //disableScroll();
        } else {
            cart.style.display = 'none';
            parent.innerHTML = '';
        }

        closeCart();

        createCart();
        totalPrice.innerHTML = getTotalPrice() + ' Р';

    }

    function preventDefault(e) {
        e.preventDefault();
    }

    function disableScroll() {
        window.addEventListener('wheel', preventDefault, {passive: false});
    }

    function enableScroll() {
        window.removeEventListener('wheel', preventDefault, {passive: false});
    }


    function createCart() {



        function getIdsProductFromCart() {

            let myCart = JSON.parse(window.localStorage.getItem('my_cart'));

            if (myCart.length == 0) {
                return false;
            }
            let idsProduct = '';

            myCart.forEach(function (elem, index) {
                idsProduct = idsProduct + elem['item_id'] + ',';
            });

            return idsProduct.slice(0, -1);
        }

        try {
            fetch(`/cart/ids/${getIdsProductFromCart()}`)
                    .then(response => response.json())
                    .then(product => {
                        let myCart = JSON.parse(window.localStorage.getItem('my_cart'));

                        myCart.forEach(function (elem, index) {
                            let itemId = elem['item_id'];
                            let countProducts = elem['count'];


                            openCartIncr(itemId, product[itemId]['img_path'], product[itemId]['title'], (product[itemId]['price'] * countProducts));

                        });


                    });
        } catch (error) {
            alert('Произошла ошибка при открытии корзины');
        }
    }

    //////////////////////////////////////////////////





    function loginOpenForm(event) {
        event.preventDefault();
        let form = document.querySelector('.wrapLoginForm');
        form.style.display = 'block';
        disableScroll();

        window.addEventListener('click', function (event) {
            if (event.target.classList.contains('wrapLoginForm')) {
                loginCloseForm(event);
            }
        });


    }
    /* если без онклика
     let loginFrom = document.querySelector('.openLoginForm');
     
     loginFrom.addEventListener("click", loginOpenForm);
     * 
     
     */
    function loginCloseForm(event) {

        let form = document.querySelector('.wrapLoginForm');
        let formLogin = document.querySelector('.form-login');
        let formRegister = document.querySelector('.register-form');

        if (event.target.classList.contains('wrapLoginForm') || event.target.closest('.wrapLoginForm')) { //второе условие для авторизации
            form.classList.add('fade-out'); // добавляем класс для анимации скрытия


            setTimeout(function () { //без setTimeout браузер не упееет перерисовать элемент
                form.style.display = 'none';
                form.classList.remove('fade-out'); // удаляем класс после окончания анимации
                enableScroll();
                formLogin.style.display = 'flex'; //надо сделать функцию, а то дублирование
                formRegister.style.display = 'none';
            }, 500);

        }

    }

//////////////////////////////////

    function createOccount(e) {
        let parent = e.closest('.form-login');
        let registerForm = document.querySelector('.register-form');
        parent.style.display = 'none';
        registerForm.style.display = 'flex';
    }

    function goLogin(e) {
        let parent = e.closest('.register-form');
        let loginForm = document.querySelector('.form-login');
        parent.style.display = 'none';
        loginForm.style.display = 'flex';
    }

    /////////////////////////////////////////////////////////


    function userRegister(event) {
        event.preventDefault();
        let dataForm = document.forms.register;
        let formSuccess = document.querySelector('.form-success');
        let formLogin = document.querySelector('.form-login');
        let formRegister = document.querySelector('.register-form');

        let email = dataForm.elements.email.value;
        let password = dataForm.elements.password.value;
        let errorEmail = formRegister.querySelector('.error-email');
        let errorPassword = formRegister.querySelector('.error-password');

        try {
            const user = {
                email: email,
                password: password
            };

            fetch(`/users/register`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(user)
            })
                    .then(response => response.json())
                    .then(user => {
                        if (user['msg'] && user['emailSend']) {
                            formLogin.style.display = 'none';
                            formRegister.style.display = 'none';
                            formSuccess.style.display = 'flex';
                            formSuccess.innerHTML = 'Регистрация прошла успешна, подтвердите вашу почту, что бы оформлять заказы';
                        }

                        if (user['email']) {
                            errorEmail.innerHTML = user['email'];
                            errorPassword.innerHTML = '';
                        }

                        if (user['password']) {
                            errorPassword.innerHTML = user['password'];
                            errorEmail.innerHTML = '';
                        }


                        console.log(user);
                    });
        } catch (error) {
            alert('Произошла ошибка при регистрации');
        }
    }

    let btnRegisterUser = document.querySelector('.userRegister');
    btnRegisterUser.addEventListener("click", userRegister);

/////////////////////////////////////////////////

    function userLogin(event) {
        event.preventDefault();
        let dataForm = document.forms.login;
        let email = dataForm.elements.email.value;
        let password = dataForm.elements.password.value;

        let formLogin = document.querySelector('.form-login');
        let errorEmail = formLogin.querySelector('.error-email');
        let errorPassword = formLogin.querySelector('.error-password');

        try {
            const user = {
                email: email,
                password: password
            };

            fetch(`/users/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(user)
            })
                    .then(response => response.json())
                    .then(user => {

                        if (user['msg']) {

                            let profil = document.querySelector('.toggle-profile');

                            let form = document.querySelector('.wrapLoginForm');
                            let styles = window.getComputedStyle(form);
                            let display = styles.getPropertyValue('display');
                            console.log(display)
                            if (display == 'block') {
                                loginCloseForm(event);
                                profil.innerHTML = '<a class="" href="/users/profile">Профиль</a>';

                            }

                        }

                        if (user['email']) {
                            errorEmail.innerHTML = user['email'];
                            errorPassword.innerHTML = '';
                        }

                        if (user['password']) {
                            errorPassword.innerHTML = user['password'];
                            errorEmail.innerHTML = '';
                        }

                        console.log(user);
                    });
        } catch (error) {
            alert('Произошла ошибка при регистрации');
        }
    }

    let btnLoginUser = document.querySelector('.userLogin');
    btnLoginUser.addEventListener("click", userLogin);


//////////////////////////////////////////////////////////

    function logout() {
        location.href = '/users/logout';
    }


////////////////////////////////



</script>


</body>

</html>