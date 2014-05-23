<?php

define('MYSQL_HOSTNAME', 'localhost');
define('MYSQL_USERNAME', 'root');
define('MYSQL_PASSWORD', '');
define('MYSQL_DATABASE', 'cep');

if (empty($_GET['cep'])) {
    header('HTTP/1.1 400 Bad request');
    die;
}

$cep = preg_replace('/\D+/', '', $_GET['cep']);

if (strlen($cep) !== 8) {
    header('HTTP/1.1 400 Bad request');
    die;
}

$pdo = new PDO('mysql:dbname=' . MYSQL_DATABASE . ';host=' . MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD);
$stmt = $pdo->prepare("SELECT tipo_logradouro, logradouro, bairro, cidade, uf FROM cep WHERE cep = :cep LIMIT 1");

if ($stmt->execute(array('cep' => $cep))) {
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $data['resultado'] = 1;
} else {
    $data = array('resultado' => 0);
}

header('HTTP/1.1 200 OK');
header('Content-Type: application/json;charset=utf-8');
echo json_encode($data);
