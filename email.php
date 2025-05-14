<?php
// Defina aqui a origem (domínio) que você considera confiável
$allowed_origin = 'https://puzzle-tecnologia.github.io';

$origin = $_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? '';

// Normaliza (remove eventual barra final)
$origin = rtrim($origin, '/');
$allowed_origin = rtrim($allowed_origin, '/');

// Se a origem não for a esperada, bloqueia
if ($origin !== $allowed_origin) {
    http_response_code(403);
    exit('403 Forbidden: origem não autorizada.');
}

// A seguir, processa somente POST válido
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit('405 Method Not Allowed: apenas POST.');
}

// Captura e valida os dados do formulário
$nome     = htmlspecialchars(trim($_POST["nome"]     ?? ''));
$email    = filter_var(trim($_POST["email"]    ?? ''), FILTER_SANITIZE_EMAIL);
$mensagem = htmlspecialchars(trim($_POST["mensagem"] ?? ''));

if (empty($nome) || empty($email) || empty($mensagem)) {
    http_response_code(400);
    exit('400 Bad Request: faltam campos obrigatórios.');
}

// Define para onde o email será enviado
$para    = 'tiago.matana@gmail.com';  // Substitua pelo seu e-mail real
$assunto = "Novo contato de $nome";

// Monta o corpo da mensagem
$corpo  = "Nome: $nome\n";
$corpo .= "E-mail: $email\n";
$corpo .= "Mensagem:\n$mensagem\n";

// Cabeçalhos do email
$headers  = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Envia e verifica
if (mail($para, $assunto, $corpo, $headers)) {
    echo "Mensagem enviada com sucesso!";
} else {
    http_response_code(500);
    echo "Erro interno: não foi possível enviar a mensagem.";
}
?>
