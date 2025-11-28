<?php
// Endpoint: login.php
// POST body JSON or form-urlencoded:
// { "email_or_username": "...", "password": "..." }
//
// Response 200: { access_token: "...", token_type: "bearer", expires_in: 3600, user: { id, email, use_name, full_name } }

require __DIR__ . '/db.php';
require __DIR__ . '/jwt.php';

header('Content-Type: application/json; charset=utf-8');

// Read input (JSON or form)
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

$emailOrUsername = isset($input['email_or_username']) ? trim($input['email_or_username']) : '';
$password = isset($input['password']) ? $input['password'] : '';

if ($emailOrUsername === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Missing credentials']);
    exit;
}

try {
    // Tìm user theo email hoặc use_name
    $stmt = $pdo->prepare('SELECT id, use_name, pass_word, full_name, email, is_verified FROM tb_user WHERE email = :key OR use_name = :key LIMIT 1');
    $stmt->execute([':key' => $emailOrUsername]);
    $user = $stmt->fetch();

    // Không tiết lộ chi tiết: trả 401 nếu không tồn tại hoặc password sai
    if (!$user || !isset($user['pass_word'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
        exit;
    }

    // Verify password
    if (!password_verify($password, $user['pass_word'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
        exit;
    }

    // Kiểm tra email đã verify chưa (tuỳ yêu cầu; hiện mặc định yêu cầu)
    if ((int)$user['is_verified'] !== 1) {
        http_response_code(403);
        echo json_encode(['error' => 'Email not verified']);
        exit;
    }

    // Tạo JWT
    $config = require __DIR__ . '/config.php';
    $secret = $config['jwt_secret'] ?? '';
    $exp = time() + ($config['jwt_exp'] ?? 3600);

    $payload = [
        'iss' => $config['jwt_issuer'] ?? 'taskmanager',
        'sub' => (int)$user['id'],
        'email' => $user['email'],
        'use_name' => $user['use_name'],
        'iat' => time(),
        'exp' => $exp,
    ];

    $token = jwt_encode($payload, $secret);

    // Trả token + thông tin user (không gửi pass)
    echo json_encode([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => $exp - time(),
        'user' => [
            'id' => (int)$user['id'],
            'email' => $user['email'],
            'use_name' => $user['use_name'],
            'full_name' => $user['full_name'],
        ],
    ]);
    exit;
} catch (Throwable $e) {
    error_log('Login error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error']);
    exit;
}