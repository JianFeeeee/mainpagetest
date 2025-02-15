<?php
// 引入配置文件
require 'config.php';

// 初始化变量
$error = '';

// 注册功能
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $full_name = $_POST['full_name'] ?? '';

    // 基本验证
    if (empty($username) || empty($password) || empty($full_name)) {
        $error = "所有字段都是必填的。";
    } else {
        try {
            // 检查用户名是否已存在
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetchColumn() > 0) {
                $error = "用户名已存在，请选择其他用户名。";
            } else {
                // 密码哈希
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // 插入新用户
                $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name) VALUES (?, ?, ?)");
                $stmt->execute([$username, $password_hash, $full_name]);

                // 注册成功，跳转到登录页面
                header("Location: login.php");
                exit();
            }
        } catch (PDOException $e) {
            $error = "注册失败: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注册</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('background.png'); /* 确保替换为你的背景图片路径 */
            background-size: cover;
            background-attachment: fixed;
            color: #333;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        header {
            background-color: rgba(173, 216, 230, 0.8);
            padding: 10px 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100px;
        }
        header h1 {
            margin: 0;
            color: #fff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }
        .main-content {
            flex: 1;
            min-width: 300px;
            padding: 20px;
            margin-right: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .main-content h2 {
            color: #333;
        }
        .main-content p {
            color: #666;
        }
        .contact-info {
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        .map {
            width: 100%;
            height: 300px;
            margin-top: 20px;
        }
        footer {
            background-color: rgba(173, 216, 230, 0.8);
            color: #333;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 14px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }
        @media (max-width: 768px) {
            footer {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="logo">
            <img src="pic/logo.jpg" alt="网站图标"> <!-- 替换为你的图标图片路径 -->
        </div>
        <div class="nav-links">
            <a href="about.html">关于我们</a>
            <a href="personal-center.html">个人中心</a>
            <a href="micro-physical.html">微体检</a>
        </div>
    </div>

    <div class="container">
        <div class="login-form" style="max-width: 400px; margin: 0 auto;">
            <h2>用户注册</h2>
            <?php if (!empty($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form method="POST">
                <div>
                    <label>用户名:</label>
                    <input type="text" name="username" required>
                </div>
                <div>
                    <label>密码:</label>
                    <input type="password" name="password" required>
                </div>
                <div>
                    <label>全名:</label>
                    <input type="text" name="full_name" required>
                </div>
                <button type="submit" name="register">注册</button>
            </form>
            <p>已有账号？<a href="login.php">立即登录</a></p>
        </div>
    </div>
</body>
</html>