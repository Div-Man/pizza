<!DOCTYPE html>
<html>
    <head>
        <title>Пицца и роллы</title>
        <link rel="stylesheet" href="/styles.css">

        <style>




        </style>

        <script>

            if (!JSON.parse(window.localStorage.getItem('my_cart'))) {
                window.localStorage.setItem('my_cart', JSON.stringify([]));

            }

            function showCountAddedToCart(id) {
                let myCart = JSON.parse(window.localStorage.getItem('my_cart'));
                let entry = myCart.find(({item_id}) => item_id == id);

                if (entry) {
                    return [entry['count'], entry['all_price']];
                }

                return false;
            }
            
             
            
        </script>


        <script>


            function createToCart(id, price) {
                let parent = document.querySelector('#block' + id);
                let div = document.createElement('div');
                div.classList.add('buy');
                div.classList.add('forCartBlock');
                parent.appendChild(div);

                let inputPriceHidden = document.createElement('input');
                inputPriceHidden.type = "hidden";
                inputPriceHidden.name = "priceHidden";
                inputPriceHidden.value = price;

                div.appendChild(inputPriceHidden);

                let inputPriceTotal = document.createElement('input');
                inputPriceTotal.type = "hidden";
                inputPriceTotal.name = "priceTotal";
                inputPriceTotal.value = "0";

                div.appendChild(inputPriceTotal);

                let inputCountHidden = document.createElement('input');
                inputCountHidden.type = "hidden";
                inputCountHidden.name = "countHidden";
                inputCountHidden.value = "0";

                div.appendChild(inputCountHidden);


                let inputIdProduct = document.createElement('input');
                inputIdProduct.type = "hidden";
                inputIdProduct.className = "id_product";
                inputIdProduct.value = id;

                div.appendChild(inputIdProduct);

                let buttonElement = document.createElement("button");
                buttonElement.className = "btn-buy";


                buttonElement.setAttribute('onclick', "toCart(this)");
                buttonElement.innerHTML = "В корзину за " + price + " Р";
                div.appendChild(buttonElement);


            }

            function cartIncr(price, id) {
                let parent = document.querySelector('#block' + id);
                let div2 = document.createElement('div');
                div2.classList.add('buy2');
                div2.classList.add('forCartBlock');

                parent.appendChild(div2);

                let inputPriceHidden2 = document.createElement('input');
                inputPriceHidden2.type = "hidden";
                inputPriceHidden2.name = "priceHidden";
                inputPriceHidden2.value = price;

                div2.appendChild(inputPriceHidden2);


                let inputPriceTotal2 = document.createElement('input');
                inputPriceTotal2.type = "hidden";
                inputPriceTotal2.name = "priceTotal";
                inputPriceTotal2.value = "0";

                div2.appendChild(inputPriceTotal2);

                let inputCountHidden2 = document.createElement('input');
                inputCountHidden2.type = "hidden";
                inputCountHidden2.name = "countHidden";
                inputCountHidden2.value = "0";

                div2.appendChild(inputCountHidden2);


                let inputIdProduct2 = document.createElement('input');
                inputIdProduct2.type = "hidden";
                inputIdProduct2.className = "id_product";
                inputIdProduct2.value = id;

                div2.appendChild(inputIdProduct2);

                let buttonElement2 = document.createElement("button");
                buttonElement2.className = "minus";
                buttonElement2.setAttribute('onclick', "quantityChange(this, 'minus')");
                buttonElement2.innerHTML = "-";
                div2.appendChild(buttonElement2);

                let span = document.createElement("span");
                span.className = "result";

                if (!showCountAddedToCart(id)) {
                    span.innerHTML = "1 шт. на " + price + ' Р';
                } else {
                    span.innerHTML = showCountAddedToCart(id)[0] + " шт. на " + showCountAddedToCart(id)[1] + ' Р';
                }

                div2.appendChild(span);


                let buttonElement3 = document.createElement("button");
                buttonElement3.className = "plus";
                buttonElement3.setAttribute('onclick', "quantityChange(this, 'plus')");
                buttonElement3.innerHTML = "+";
                div2.appendChild(buttonElement3);
            }





            function openCartIncr(id, img, title, price) {
                let parent = document.querySelector('.dynamic-layout-js');
                let div2 = document.createElement('div');


                let tr = document.createElement('tr');
                tr.classList.add('product-wrap-item');
                tr.setAttribute("id", "cart-block" + id);
                parent.appendChild(tr);


                let td = document.createElement('td');
                td.classList.add('CellPic');
                tr.appendChild(td);


                let picture = document.createElement('picture');
                td.appendChild(picture);

                let image = document.createElement('img');
                image.setAttribute("src", "/img/" + img);
                image.setAttribute("width", "80");
                picture.appendChild(image);

                let td2 = document.createElement('td');
                tr.appendChild(td2);

                let span = document.createElement('span');
                span.style.height = '40px';
                span.style.width = '100%';
                span.style.display = 'flex';
                span.style.alignItems = 'center';
                span.innerHTML = title;
                td2.appendChild(span);


                let td3 = document.createElement('td');
                tr.appendChild(td3);



                let div = document.createElement("div");
                div.classList.add('buy2');
                div.classList.add('plusminus');
                td3.appendChild(div);
                
                let td4 = document.createElement('td');
                tr.appendChild(td4);
                
                
                let divTd4 = document.createElement("div");
                divTd4.classList.add('all-price');
                td4.appendChild(divTd4);
                divTd4.innerHTML = price + ' Р';
                
                
                let inputPriceHidden2 = document.createElement('input');
                inputPriceHidden2.type = "hidden";
                inputPriceHidden2.name = "priceHidden";
                inputPriceHidden2.value =  price;

                div.appendChild(inputPriceHidden2);


                let inputPriceTotal2 = document.createElement('input');
                inputPriceTotal2.type = "hidden";
                inputPriceTotal2.name = "priceTotal";
                inputPriceTotal2.value = "0";

                div.appendChild(inputPriceTotal2);

                let inputCountHidden2 = document.createElement('input');
                inputCountHidden2.type = "hidden";
                inputCountHidden2.name = "countHidden";
                inputCountHidden2.value = "0";

                div.appendChild(inputCountHidden2);


                let inputIdProduct2 = document.createElement('input');
                inputIdProduct2.type = "hidden";
                inputIdProduct2.className = "id_product";
                inputIdProduct2.value = id;

                div.appendChild(inputIdProduct2);


                let buttonElement2 = document.createElement("button");
                buttonElement2.className = "minus";
                buttonElement2.setAttribute('onclick', "quantityChange(this, 'minus')");
                buttonElement2.innerHTML = "-";
                div.appendChild(buttonElement2);

                let span2 = document.createElement("span");
                span2.className = "result";
                span2.innerHTML = showCountAddedToCart(id)[0];
                

                div.appendChild(span2);


                let buttonElement3 = document.createElement("button");
                buttonElement3.className = "plus";
                buttonElement3.setAttribute('onclick', "quantityChange(this, 'plus')");
                buttonElement3.innerHTML = "+";
                div.appendChild(buttonElement3);
                

            }

            //////////////////////////////////////////////////////////////////////////////




        </script>
    </head>

    <body class="body">
        