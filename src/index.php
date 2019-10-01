<?php

require('vendor/autoload.php');

$container = new \Pimple\Container();
$container['pdo'] = function() {
    $databaseHost = 'database';
    $databaseName = $_ENV['MYSQL_DATABASE'];
    $dsn = "mysql:host=$databaseHost;dbname=$databaseName;charset=utf8";
    return new PDO(
        $dsn,
        $_ENV['MYSQL_USER'],
        $_ENV['MYSQL_PASSWORD']
    );
};

$seed = new \App\Seed();
$seed->setPDO($container['pdo']);
$seed->createDatabase($_ENV['MYSQL_DATABASE'], $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD']);
$seed->addSomeUsers();

$app = new \App\Main();
$app->setPDO($container['pdo']);
$parameters = [
    // expected parameter
    'Jacob',
    // sql injection attack example
    "Jacob' OR 1=1;"
];

foreach ($parameters as $parameter) {
    echo PHP_EOL . " **** [$parameter] ****" . PHP_EOL;
    $app->unsafeQuery($parameter);
    $app->preparedStatement($parameter);
    $app->escapeStrings($parameter);
}

