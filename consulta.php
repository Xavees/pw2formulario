<?php

require_once 'conexao.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode([
        'sucesso' => false,
        'erro' => 'Envie os dados via formulario (POST).'
    ]));
}

// pega o email corretamente
$email = $_POST['email'] ?? '';

if ($email === '') {
    http_response_code(422);
    exit(json_encode([
        'sucesso' => false,
        'erro' => 'O campo "email" é obrigatorio.'
    ]));
}

try {
    $pdo->exec('USE `' . DB_NAME . '`');

    $stmt = $pdo->prepare('SELECT * FROM `cadastros` WHERE email = ?');
    $stmt->execute([$email]);

    $cadastros = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cadastros) {
        echo json_encode([
            'sucesso' => true,
            'cadastro' => $cadastros
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'sucesso' => false,
            'erro' => 'Cadastro não encontrado'
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'erro' => 'Erro ao consultar o banco de dados: ' . $e->getMessage()
    ]);
}


