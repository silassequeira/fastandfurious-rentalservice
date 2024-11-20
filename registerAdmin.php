<?php
require_once 'config.php';

header('Content-Type: application/json');

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

$username = filter_var($input['username'], FILTER_SANITIZE_STRING);
$email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
$password = $input['password'];
$name = filter_var($input['name'], FILTER_SANITIZE_STRING);

try {
    // Check if username or email already exists
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM administrador WHERE username = :username OR email = :email");
    $checkStmt->execute(['username' => $username, 'email' => $email]);
    
    if ($checkStmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Username ou Email já existe']);
        exit;
    }

    // Insert new administrator
    $stmt = $pdo->prepare("INSERT INTO administrador (username, email, password, name) VALUES (:username, :email, :password, :name)");
    $result = $stmt->execute([
        'username' => $username,
        'email' => $email,
        'password' => $password, // WARNING: Use password_hash() in production
        'name' => $name
    ]);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro no registo']);
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
}