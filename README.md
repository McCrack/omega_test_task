<h2>Тестовое задание</h2>

<h4>Для реализации ипользовался фреймворк Laravel, MySQL, PHP 7.4.3</h4>

<ol>
    <li>
        Клонировать проект
        <br>
        <code>git clone https://github.com/McCrack/omega_test_task.git</code>
    </code>

   <li>Установить зависимости
        <br>
        <code>composer install</code>
   </li>

   <li>Создать базу данных и настроить подключение в файле <b>.env</b></li>

   <li>
        Выполнить миграции и наполнить базу фейковыми данными (займет время)
        <br>
        <code>php artisan migrate --seed</code>
   </li>
<ol>
    <br><br>
    <p>Весь код по заданию находится в папке <code>App/Task</code> плюс три модели в папке App<p>
<h3>2. Написать SQL-запросы для получения...</h3>
<p>
    Raw запросы приведены вконце этого файл. Для демонстрации выполнить в консоли:
    <br>
    <code>php artisan tinker</code>
</p>
<p>
   Нам понадобится список компаний, выполните:
    <br>
   <code>>>(new App\Task\Repository)->getCompaniesList()</code>
</p>



<p>
    2.1. Количество всех клиентов, подписанных хоть на один тариф (по компаниям)
   <br>
   Выполнить в консоли:
   <br>
   <code>>>>(new App\Task\Repository)->company('<имя компании>')->customers()->count()</code>
</p>
<p>
2.2  Количество неактивных клиентов, подписанных на тарифы (по компаниям)
	<br>
    Выполнить в консоли:
    <br>
    <code>>>>(new App\Task\Repository)->company('<имя компании>')->customers()->active(false)->count()</code>
</p>
<p>
2.3 Список тарифов и количество активных клиентов подписанных на эти тарифы (по 	компаниям)
    <br>
    Выполнить в консоли:
    <br>
    <code>
        >>>(new App\Task\Repository)
            ->company('<имя компании>')
            ->tariffsWithActiveCustomers()
            ->active()
            ->get()
    </code>
</p>
<p>
    2.4 Список активных клиентов и тарифы, на которые они подписаны
    <br>
    Выполнить  в консоли:
    <br>
    <code>(new App\Task\Repository)->company('<имя компании>')->customersWithTariffs()->active()->get()</code>
</p>
<h3>3. Написать консольный php-скрипт для формирования отчетов компаний</h3>
<p>
    Выполните в консоли:
    <br>
    <code>>>>(new App\Task\Omega)->export()</code>
</p>
<p>
    Для экпорта одной конкретной компании можо указать ее имя параметром метода export
    <br>
    <code>
    >>>(new App\Task\Omega)->export("Halvorson-O'Hara")</code>
    </code>
</p>
<p>
Экспортированые файлы можно найти в папке <code>storage/app/public</code>
</p>

<h3>Raw запросы</h3>

<p>
Количество всех клиентов, подписанных хоть на один тариф (по компаниям)
</p>
<pre>
<code>
SELECT
	COUNT(DISTINCT customer_id) AS cnt
FROM
	customer_tariff
WHERE
    tariff_id IN (
	    SELECT
		    tariffs.id
	    FROM `companies`
	    JOIN `tariffs` ON `tariffs`.`company_id` = `companies`.`id`
        WHERE `companies`.`name` = 'Monahan-Marquardt'
    )
    
</code>
</pre>



<p>
Количество неактивных клиентов, подписанных на тарифы (по компаниям)
</p>
<pre>
<code>
SELECT
	COUNT(DISTINCT customer_id) AS cnt
FROM
	customer_tariff
JOIN
	customers ON customers.id = customer_tariff.customer_id
WHERE
    is_active = 0
    AND tariff_id IN (
	    SELECT
		    tariffs.id
	    FROM `companies`
	    JOIN `tariffs` ON `tariffs`.`company_id` = `companies`.`id`
        WHERE `companies`.`name` = 'Monahan-Marquardt'
    )
    
</code>
</pre>



<p>
Список тарифов и количество активных клиентов подписанных на эти тарифы (по 	компаниям)
</p>
<pre>
<code>
SELECT
    tariffs.name AS tariff,
    COUNT(DISTINCT customer_id) AS customers
FROM
    `customer_tariff`
JOIN
    `customers` ON customers.id = customer_tariff.customer_id
JOIN
    `tariffs` ON tariffs.id = customer_tariff.tariff_id
JOIN
    `companies` ON companies.id = tariffs.company_id
WHERE
    companies.name = 'Monahan-Marquardt'
    AND customers.is_active > 0
GROUP BY tariff_id

</code>
</pre>


<p>
Список активных клиентов и тарифы, на которые они подписаны
</p>
<pre>
<code>
SELECT
    CONCAT(customers.first_name, ' ', customers.first_name) AS name,
    customers.phone,
    tariffs.name as tariff
FROM
    `customer_tariff`
JOIN
    `customers` ON customers.id = customer_tariff.customer_id
JOIN
    `tariffs` ON tariffs.id = customer_tariff.tariff_id
WHERE
    customers.is_active > 0
    
</code>
</pre>
