<?php
session_start();
include 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    
    if (empty($username) || empty($password)) {
        $error = "Please fill all fields";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Check if username exists
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();
        
        if ($check->num_rows > 0) {
            $error = "Username already exists";
        } else {
            // Insert new user with role
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $hashed_password, $role);
            
            if ($insert->execute()) {
                $insert->close();
                $check->close();
                header("Location: login.php?success=1");
                exit();
            } else {
                $error = "Registration failed";
            }
        }
        $check->close();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>JET - Register</title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body { 
            font-family: 'Arial', sans-serif; 
            background: #ffffff;   /* White background */
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
        }
        
        .register-container { 
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.15); 
            width: 380px;  /* Reduced width */
            text-align: center;
        }
        
        .company-header {
            margin-bottom: 20px;  /* Reduced margin */
            padding-bottom: 15px;  /* Reduced padding */
            border-bottom: 2px solid #f0f0f0;
        }
        
        .company-header img {
            width: 150px;   /* Slightly smaller image */
            height: auto;
            margin-bottom: 8px;  /* Reduced margin */
        }
        
        .register-form {
            text-align: left;
        }
        
        .form-group { 
            margin-bottom: 15px;   /* Reduced margin */
        }
        
        label { 
            display: block; 
            margin-bottom: 6px;   /* Reduced margin */
            color: #2c3e50; 
            font-weight: 600;
            font-size: 13px;   /* Slightly smaller font */
        }
        
        input, select { 
            width: 100%; 
            padding: 10px;   /* Reduced padding */
            border: 2px solid #e0e0e0; 
            border-radius: 6px; 
            font-size: 14px;   /* Slightly smaller font */
            transition: all 0.3s ease;
        }
        
        input:focus, select:focus { 
            outline: none; 
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .register-btn { 
            width: 100%; 
            padding: 12px;   /* Reduced padding */
            background: linear-gradient(135deg, #2ecc71, #27ae60); 
            color: white; 
            border: none; 
            border-radius: 6px; 
            font-size: 14px;   /* Slightly smaller font */
            font-weight: 600;
            cursor: pointer; 
            transition: all 0.3s ease;
            margin-top: 8px;   /* Reduced margin */
        }
        
        .register-btn:hover { 
            background: linear-gradient(135deg, #27ae60, #229954);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }
        
        .error { 
            background: #e74c3c; 
            color: white; 
            padding: 10px;   /* Reduced padding */
            border-radius: 6px; 
            margin-bottom: 15px;   /* Reduced margin */
            text-align: center;
            font-size: 13px;   /* Slightly smaller font */
        }
        
        .success { 
            background: #2ecc71; 
            color: white; 
            padding: 10px;   /* Reduced padding */
            border-radius: 6px; 
            margin-bottom: 15px;   /* Reduced margin */
            text-align: center;
            font-size: 13px;   /* Slightly smaller font */
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;   /* Reduced margin */
            padding-top: 15px;   /* Reduced padding */
            border-top: 1px solid #f0f0f0;
            color: #7f8c8d;
            font-size: 13px;   /* Slightly smaller font */
        }
        
        .login-link a {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .form-title {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;   /* Reduced margin */
            font-size: 20px;   /* Slightly smaller font */
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <!-- Company Header -->
        <div class="company-header">
            <img src="jet.jpg" alt="Company Logo">
        </div>
        
        <!-- Register Form -->
        <div class="register-form">
            <div class="form-title">Create Account</div>
            
            <?php if ($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="success">Registration successful! Please login.</div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Choose a username" required>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="Confirm your password" required>
                </div>
                
                <div class="form-group">
                    <label>Account Type</label>
                    <select name="role" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <button type="submit" class="register-btn">Create Account</button>
            </form>
            
            <div class="login-link">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        </div>
    </div>
</body>
</html>