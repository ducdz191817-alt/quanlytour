<?php

require __DIR__ . '/db.php';
require __DIR__ . '/lib_send_email.php';

header('Content-Type: application/json; charset=utf-8');

// Đọc input (hỗ trợ JSON body hoặc form)
$input = null;
$raw = file_get_contents('php://input');
if ($raw) {
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        $input = $decoded;
    }
}
if ($input === null) {
    $input = $_POST;
}

$email = isset($input['email']) ? trim($input['email']) : '';
$password = isset($input['password']) ? $input['password'] : '';
$full_name = isset($input['full_name']) ? trim($input['full_name']) : null;
$use_name = isset($input['use_name']) ? trim($input['use_name']) : null;
$phone = isset($input['phone']) ? trim($input['phone']) : null;

// Validate
$errors = [];
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Email không hợp lệ';
}
if (!is_string($password) || strlen($password) < 8) {
    $errors['password'] = 'Mật khẩu phải có ít nhất 8 ký tự';
}
if ($use_name !== null && strlen($use_name) > 225) {
    $errors['use_name'] = 'Tên đăng nhập quá dài';
}
if ($full_name !== null && strlen($full_name) > 225) {
    $errors['full_name'] = 'Họ tên quá dài';
}
if ($phone !== null && strlen($phone) > 225) {
    $errors['phone'] = 'Số điện thoại quá dài';
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['errors' => $errors]);
    exit;
}

try {
    // Kiểm tra email đã tồn tại chưa
    $stmt = $pdo->prepare('SELECT id FROM tb_user WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    $existing = $stmt->fetch();
    if ($existing) {
        http_response_code(409);
        echo json_encode(['error' => 'Email đã được đăng ký']);
        exit;
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Tạo verification token
    $token = bin2hex(random_bytes(32));

    // Lưu user vào tb_user (cột theo hình của bạn)
    $sql = 'INSERT INTO tb_user (use_name, pass_word, full_name, email, phone, verification_token, is_verified, created_at, updated_at)
            VALUES (:use_name, :pass_word, :full_name, :email, :phone, :token, 0, NOW(), NOW())';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':use_name' => $use_name,
        ':pass_word' => $passwordHash,
        ':full_name' => $full_name,
        ':email' => $email,
        ':phone' => $phone,
        ':token' => $token,
    ]);

    $newId = $pdo->lastInsertId();

    // Gửi email xác thực (không block response)
    $sent = send_verification_email($email, $token);

    http_response_code(201);
    echo json_encode([
        'message' => 'User created',
        'user' => [
            'id' => $newId,
            'use_name' => $use_name,
            'full_name' => $full_name,
            'email' => $email,
            'is_verified' => false,
        ],
        'verification_email_sent' => $sent,
    ]);
    exit;
} catch (Throwable $e) {
    error_log('Registration error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error']);
    exit;
}