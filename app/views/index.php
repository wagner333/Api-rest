<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once "/home/wagner/Downloads/Mini-FrameWork-2.0-main/app/controller/UserController.php";

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados JSON da requisição
    $data = json_decode(file_get_contents("php://input"), true);
    $nome = $data['name'];
    $email = $data['email'];
    $senha = $data['password'];

    $userController = new UserController();
    $message = $userController->registerUser($nome, $email, $senha);

    // Verifica se o cadastro foi bem-sucedido
    if ($message) {
    echo json_encode(['success' => true, 'message' => $message]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar usuário.']);
}

}
?>
<h1>a</h1>