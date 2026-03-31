<?php

// ---------------------------------------------------
// Configuração - altere apenas estas quatros linhas
// ---------------------------------------------------

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME' , 'db_formulario');

// ---------------------------------------------------
// Passo 1 - Responde sempre em JSON (sem HTML)
// ---------------------------------------------------

header('Content-Type: application/json; charset=utf-8');

// ---------------------------------------------------
// PASSO 2 - Garanta que veio de um formulario
// ---------------------------------------------------

if ($_SERVER['REQUEST_METHOD']  !== 'POST') {
    http_response_code(405);
        exit(json_encode(['sucesso' => false, 'erro' => 'Envie os dados via formulario (POST).']));

}

// ---------------------------------------------------
// PASSO 3 - le os campos e valida
// ---------------------------------------------------

$campos = array_map('trim', $_POST); // remove espaços em branco
$erros =  [];

    foreach($campos as $nome => $valor) {
            if ($valor === '' ) {
                $erros[] = "O campo \"$nome\" não pode ficar vazio.";


            }


    }

    if (isset($campos['email']) && !filter_var($campos['email'], FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'O email informado é invalido';




    }

    if ($erros) {
        http_response_code(422);
        exit(json_encode(['Sucesso' => false, 'erros' => $erros]));


    }
// ---------------------------------------------------
// PASSO 4 - Conectando ao MYSQL e Criando o Banco 
// ---------------------------------------------------

try {
    $pdo = new PDO('mysql:host=' . DB_HOST, DB_USER , DB_PASS);
        $pdo-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Criar o banco de dados se ele ainda nao existir
  $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4');
     $pdo->exec('USE `' . DB_NAME . '`');
     
// ---------------------------------------------------
// PASSO 5 - Cria a tabela se ela ainda não existir
// ---------------------------------------------------

$pdo->exec('CREATE TABLE IF NOT  EXISTS `cadastros` (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 criado_Em DATETIME DEFAULT  CURRENT_TIMESTAMP
    ) ENGINE =InnoDB DEFAULT CHARSET=utf8mb4');

// ---------------------------------------------------
// PASSO 6 - Adicionando novas colunas automaticamente
// cada campo do funcionario vira uma coluna
// ---------------------------------------------------
$colunas_existentes = $pdo-> query ('SHOW COLUMNS FROM `cadastros`')-> fetchAll (PDO::FETCH_COLUMN);

    foreach (array_keys($campos) as $campo ){
        $coluna = preg_replace('/[^a-zA-Z0-9_]/', '_' , $campo); // só letras e numeros e _
            if (!in_array($coluna, $colunas_existentes)) {
                $pdo->exec('ALTER TABLE `cadastros` ADD COLUMN' . $coluna . '` VARCHAR(500)');



            }




    }

// ---------------------------------------------------    
// PASSO 7 - Salva os dados no banco
// ---------------------------------------------------
$colunas = array_map(fn($c)) => '`' . preg_replace ('/[^a-zA-Z0-9_]/', '_' , $c) . '`', arrays_keys(($campos));
$binds   = array_map(fn($c)) => ':' . preg_replace ('/[^a-zA-Z0-9_]/', '_' , $c), arrays_keys ($campos);
 $valores = arrays_combine($binds, arrays_values ($campos));

 $sql = 'INSERT INTO `cadastros` (' . implode (',' , $colunas) . ') VALUES (' . implode (',' , $binds) . ')';
$stmt = $pdo -> prepare($sql);
$stmt -> execute ($valores);


// ---------------------------------------------------
// PASSO 8 - RETORNA SUCESSO
// ---------------------------------------------------

    echo json_encode ([
      'sucesso' => true,
      'mensagem' => 'Cadastro salvo com sucesso',
      'id' => (int) $pdo->lasInsertId(),

    ]);




  


}   catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);

}

