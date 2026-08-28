<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Information System</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, Helvetica, sans-serif;
        }

        body{
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:linear-gradient(135deg,#1565c0,#42a5f5,#64b5f6);
        }

        .login-box{
            width:500px;
            background:#fff;
            border-radius:18px;
            padding:40px;
            box-shadow:0 15px 35px rgba(0,0,0,.25);
        }

        .logo{
    width:130px;
    height:130px;
    margin:0 auto 15px;
    display:flex;
    justify-content:center;
    align-items:center;
}

.logo img{
    width:130px;
    height:130px;
    object-fit:contain;
}

        h2{
            text-align:center;
            color:#1565c0;
            margin-bottom:8px;
        }

        .subtitle{
            text-align:center;
            color:#666;
            margin-bottom:30px;
            font-size:15px;
        }

        label{
            display:block;
            margin-bottom:8px;
            font-weight:bold;
            color:#333;
        }

        .input-group{
            display:flex;
            align-items:center;
            border:1px solid #ccc;
            border-radius:8px;
            overflow:hidden;
            margin-bottom:20px;
        }

        .input-group i{
            width:50px;
            text-align:center;
            color:#1565c0;
            font-size:18px;
        }

        .input-group input{
            width:100%;
            padding:14px;
            border:none;
            outline:none;
            font-size:15px;
        }

        .btn-login{
            width:100%;
            padding:14px;
            border:none;
            border-radius:8px;
            background:#1565c0;
            color:#fff;
            font-size:17px;
            font-weight:bold;
            cursor:pointer;
            transition:.3s;
        }

        .btn-login:hover{
            background:#0d47a1;
            transform:translateY(-2px);
        }

        .footer{
            margin-top:25px;
            text-align:center;
            color:#777;
            font-size:13px;
        }

    </style>

</head>
<body>

<div class="login-box">

    <div class="logo">
    <img src="logo.png" alt="SIBTI Logo">
</div>

   <h2>Southern Institute of Business and Technology, Inc.</h2>

<p class="subtitle">
    Student Information System
</p>
    <form action="login.php" method="POST">

        <label>Username</label>

        <div class="input-group">
            <i class="fas fa-user"></i>
            <input
                type="text"
                name="username"
                placeholder="Enter Username"
                required>
        </div>

        <label>Password</label>

        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input
                type="password"
                name="password"
                placeholder="Enter Password"
                required>
        </div>

        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt"></i>
            Login
        </button>

    </form>

    <div class="footer">
        © <?php echo date("Y"); ?> Student Information System
    </div>

</div>

</body>
</html>