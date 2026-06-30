<?php

// Isama ang configuration para sa API URL
include 'config/api_config.php';
$error = '';
$success = '';



// I-check kung pinindot na ang login button POST request
if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if(empty($username) || empty($password)){
                $error = "ALL fields are required" . htmlspecialchars($error); // ERROR: Validation
        }else{
                // 1. I-prepare ang payload data bilang JSON string
                $payload = json_encode([
                        'username' => $username,
                        'password' => $password
                ]);

                // 2. I-setup ang cURL request papunta sa Flask `/login` endpoint

                $url = 'API_BASE_URL' . '/login';
                $ch = curl_init($url);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ([
                        'Content-Type : application/jason',
                        'Content-Length' . strlen($payload)

                ]));
   
                // 3. I-execute ang cURL at kunin ang response at status code
                $response = curl_exec($ch);
                $HttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                 // 4. I-decode ang naging sagot ng Flask backend
                 $data = json_decode($response, true);

                 if($HttpCode === 200){
                        $success = "Successful Logged in" . htmlspecialchars($data ['user']['username']);
                 }
                 else{
                        $error = "Error, Login failed" . htmlspecialchars($error);
                 }
        }
        

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
</head>
<body>
        <?php if (!empty($error)): ?>
                <p style="color: red;"> <?php echo htmlspecialchars($error) ?></p>
                <?php endif; ?>

                <?php if ($success): ?>
                <p style="color: green;"> <?php echo htmlspecialchars($success); ?></p>
                <?php endif; ?>
        <form method="POST" action="login.php">

                <input type="text" name='username' placeholder="username"><br>
                <input type="password" name='password' placeholder="password">
                <button type="submit">Login</button>
        </form>
</body>
</html>

