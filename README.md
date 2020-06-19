<h3>Тестовое задание</h3>

<h4>Для реализации ипользовался фреймворк Laravel, MySQL, PHP 7.4.3</h4>

<ol>
    <li>
        Клонировать проект
        <code>git clone https://github.com/McCrack/omega_test_task.git</code>
    </code>

   <li>Установить зависимости
        <code>composer install</code>
   </li>

   <li>Создать базу данных и настроить подключение в файле <b>.env</b></li>

   <li>
        Выполнить миграции и наполнить базу фейковыми данными (займет время)
        <code>php artisan migrate --seed</code>
   </li>
<ol>
<p>
Весь код по заданию находится в папке App/Omega плюс три модели в папке App
<p>
<h3>2. Написать SQL-запросы для получения...</h3>
<div>
    Raw запросы приведены вконце этого файл. Для демонстрации выполнить в консоли:
    <code>php artisan tinker</code>
</div>
<div>
   Нам понадобится список компаний, выполните

   <code>>>(new App\Omega\Repository)->getCompaniesList()</code>
</div>



2.1. Количество всех клиентов, подписанных хоть на один тариф (по компаниям)
    
    Выполнить в консоли
    (new App\Task\Repository)->company('<имя компании>')->customers()->count()


2.2  Количество неактивных клиентов, подписанных на тарифы (по компаниям)
	
    Выполнить  в консоли	
	(new App\Task\Repository)->company('<имя компании>')->customers()->active(false)->count()


2.3 Список тарифов и количество активных клиентов подписанных на эти тарифы (по 	компаниям)

    Выполнить  в консоли
    (new App\Task\Repository)->company('<имя компании>')->tariffsWithActiveCustomers()->active()->get()

2.4 Список активных клиентов и тарифы, на которые они подписаны
    
    Выполнить  в консоли
    (new App\Task\Repository)->company('<имя компании>')->customersWithTariffs()->active()->get()


3. Написать консольный php-скрипт для формирования отчетов компаний

    Выполните в консоли
    (new App\Task\Omega)->export()


    Для экпорта одной конкретной компании можо указать ее имя параметром метода export
    (new App\Task\Omega)->export("Halvorson-O'Hara")


Экспортированые файлы можно найти в папке storage/app/public



Raw запросы


Количество всех клиентов, подписанных хоть на один тариф (по компаниям)

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


Количество неактивных клиентов, подписанных на тарифы (по компаниям)

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

Список тарифов и количество активных клиентов подписанных на эти тарифы (по 	компаниям)

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


Список активных клиентов и тарифы, на которые они подписаны

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
