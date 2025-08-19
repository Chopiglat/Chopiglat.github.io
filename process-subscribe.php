<?php
// 预约关注处理脚本
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// 获取POST数据
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$interest = isset($_POST['interest']) ? $_POST['interest'] : [];
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// 验证数据
$errors = [];

if (empty($name)) {
    $errors[] = '姓名不能为空';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = '请输入有效的邮箱地址';
}

if (empty($phone)) {
    $errors[] = '联系电话不能为空';
}

if (empty($interest)) {
    $errors[] = '请至少选择一个感兴趣的服务';
}

// 如果有错误，返回错误信息
if (!empty($errors)) {
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// 处理数据
$interest_str = is_array($interest) ? implode(', ', $interest) : $interest;
$timestamp = date('Y-m-d H:i:s');

// 保存到文件
$data = [
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'interest' => $interest_str,
    'message' => $message,
    'timestamp' => $timestamp,
    'ip' => $_SERVER['REMOTE_ADDR']
];

// 确保订阅数据目录存在
if (!is_dir('subscription_data')) {
    mkdir('subscription_data', 0755, true);
}

// 保存到CSV文件
$csv_file = 'subscription_data/subscriptions.csv';
$is_new_file = !file_exists($csv_file);

$fp = fopen($csv_file, 'a');
if ($fp) {
    if ($is_new_file) {
        fputcsv($fp, ['姓名', '邮箱', '电话', '兴趣', '留言', '时间', 'IP地址']);
    }
    fputcsv($fp, array_values($data));
    fclose($fp);
}

// 保存到JSON文件
$json_file = 'subscription_data/subscriptions.json';
$existing_data = [];
if (file_exists($json_file)) {
    $existing_data = json_decode(file_get_contents($json_file), true);
    if (!is_array($existing_data)) {
        $existing_data = [];
    }
}

$existing_data[] = $data;
file_put_contents($json_file, json_encode($existing_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// 发送邮件通知（可选）
$to = "your-email@example.com"; // 替换为你的邮箱
$subject = "新的预约关注 - " . $name;
$body = "姓名: $name\n邮箱: $email\n电话: $phone\n兴趣: $interest_str\n留言: $message\n时间: $timestamp";

// 注意：需要配置邮件服务才能使用
// mail($to, $subject, $body);

// 返回成功响应
echo json_encode([
    'success' => true,
    'message' => '预约成功！我们会尽快与您联系。',
    'data' => [
        'name' => $name,
        'email' => $email,
        'timestamp' => $timestamp
    ]
]);
?>