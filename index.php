<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Интернет-магазин</title>
</head>
<body>
<?php
require 'Products.php'; //подключаем класс Products
$myDB = new Products('localhost', 3306, 'shop_db', 'root', ''); //создаем подключение
?>

<div class="container">
    <h1>Список товаров интернет магазина</h1>
    <div class="flex">
        <div class="php_sort_filter">
            <form method="get" class="php__form" action="index.php">
                <select name="price">
                    <option value="asc" <?= $_GET['price'] == 'asc' ? 'selected' : '' ?> >По возрастанию</option>
                    <option value="desc" <?= $_GET['price'] == 'desc' ? 'selected' : '' ?>>По убыванию</option>
                    <option value="default" <?= $_GET['price'] == 'default' ? 'selected' : '' ?>>стандарт</option>
                </select>
                <span class="php__filter">
                <label for="filter">Фильтр по названию</label>
                <input type="text" value="<?= $_GET['filter'] ?? '' ?>" id="filter" name="filter">
                <input type="submit" class="button" value="filter&sort">
                </span>
            </form>
        </div>
        <div class="js_sort">
            <span class="js__filter-sort">
            <button class="button" onclick="sortProductsByPrice('asc')" id="sort_js_asc">price_asc(JS)
            </button>
            <button class="button" onclick="sortProductsByPrice('desc')" id="sort_js_desc">price_desc(JS)
            </button>
                <span>
            <label for="filter_js">Фильтрация товаров (JS)</label>
            <input type="text" id="filter_js">
                    </span>
        </span>
        </div>
    </div>
    <table>
        <thead>
        <tr>
            <th>
                Название товара
            </th>
            <th>Описание товара</th>
            <th>Цена товара</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $filter = $_GET['filter'] ?? ''; //считываем значения фильтра из get-запроса, если оно пустое, то присваеваем ''
        $product_list = $myDB->list($_GET['price'] ?? 'default', $filter);
        if (isset($product_list) && count($product_list) > 0) {
            foreach ($product_list as $product) {
                ?>
                <tr data-price="<?= $product['price'] ?>">
                    <td>
                        <?= $product['name'] ?>
                    </td>
                    <td>
                        <?= $product['description'] ?>
                    </td>
                    <td>
                        <?= $product['price'] ?>
                    </td>
                </tr>
            <?php }
        } else {
            ?>
            <tr>
                <td colspan="3">Нет данных для отображения</td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
<script>
    //скрипт для сортировки
    function sortProductsByPrice(sort) { //принимает на вход критерий сортировки (asc - по возрастанию, desc - по убыванию)
        let products = document.querySelectorAll('tr[data-price]'); // получаем все строки таблицы, которые будем сортировать
        let sorted = [] //создаем массив для отсортированных значений
        if (sort === 'asc') { // в случае если сортируем по возрастанию
            sorted = [...products].sort((a, b) => { // вызываем функцию sort()
                const priceElA = a.dataset['price']; // находим цену элемента a
                const priceElB = b.dataset['price']; // находим цену элемента b
                return priceElA - priceElB; // возвращаем разность цен A-B
            });
        } else if (sort === 'desc') { //в случае если сортируем по убыванию
            sorted = [...products].sort((a, b) => {
                const priceElA = a.dataset['price'];
                const priceElB = b.dataset['price'];
                return priceElB - priceElA; //возвращаем разность цен B-A
            });
        }
        const resultEl = document.querySelector("tbody"); // находим tbody, в который будем записывать результаты
        resultEl.innerHTML = null; //обнуляем его содержимое
        sorted.forEach(el => resultEl.appendChild(el)); //записываем результат в таблицу
    }

    //скрипт для фильтрации
    let input = document.getElementById('filter_js') //находим наш инпут для фильтрации
    input.oninput = function () {
        setTimeout(find_it, 100); //добавляем ему событие oninput с таймаутом 100 мс. При вводе данных в инпут через 100 мс должна выполняться функция find_it
    };


    function find_it() {
        let table_body_rows = document.querySelectorAll('tr[data-price]');   //выбираем строки по которым ищем
        let search_text = new RegExp(document.getElementById('filter_js').value, 'i');   //считываем искомый текст
        for (let i = 0; i < table_body_rows.length; i++) {  //проходимся по строкам
            let flag_success = false   //станет true, если есть совпадение в строке
            let td_row = table_body_rows[i].getElementsByTagName('td')[0]   //выбираем ячейку строки, в котором название
            if (td_row.textContent.match(search_text)) {
                flag_success = true //если есть совпадение переводим flag_success в true
            }
            if (flag_success) {
                table_body_rows[i].style.display = ""
            } else { //ставим display none всем строкам, у которых нет совпадений по поиску
                table_body_rows[i].style.display = "none"
            }
        }
    }


</script>
</body>
</html>