<?php
include "config.php";

if(isset($_POST['but_submit'])){

    $uname = mysqli_real_escape_string($con,$_POST['txt_uname']);
    $password = mysqli_real_escape_string($con,$_POST['txt_pwd']);

    if ($uname != "" && $password != ""){

        $sql_query = "select count(*) as cntUser from users where username='".$uname."' and password='".$password."'";
        $result = mysqli_query($con,$sql_query);
        $row = mysqli_fetch_array($result);

        $count = $row['cntUser'];

        if($count > 0){
            $_SESSION['uname'] = $uname;
            header('Location: index.html');
            exit;
        }else{
            $error = "Wrong username or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login – Blood Donation App</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-red-500 via-rose-500 to-pink-600 flex items-center justify-center px-4">

  <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 relative overflow-hidden">
    
    <!-- Glow Effect -->
    <div class="absolute -top-20 -right-20 w-40 h-40 bg-pink-400 opacity-30 rounded-full blur-3xl"></div>

    <h1 class="text-3xl font-bold text-gray-800 text-center mb-2">
      Welcome Back 👋
    </h1>
    <p class="text-gray-500 text-center mb-8">
      Login to your account
    </p>

    <?php if(isset($error)) { ?>
      <div class="mb-4 text-sm text-red-600 bg-red-100 px-4 py-2 rounded-lg">
        <?= $error ?>
      </div>
    <?php } ?>

    <form method="post" class="space-y-5">

      <div>
        <label class="text-sm text-gray-600">Username</label>
        <input 
          type="text" 
          name="txt_uname"
          required
          class="w-full mt-1 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-rose-500 focus:outline-none"
          placeholder="Enter your username"
        />
      </div>

      <div>
        <label class="text-sm text-gray-600">Password</label>
        <input 
          type="password" 
          name="txt_pwd"
          required
          class="w-full mt-1 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-rose-500 focus:outline-none"
          placeholder="Enter your password"
        />
      </div>

      <button 
        type="submit" 
        name="but_submit"
        class="w-full bg-gradient-to-r from-rose-500 to-pink-600 text-white py-3 rounded-xl font-semibold shadow-lg hover:scale-[1.02] transition-all"
      >
        Login
      </button>

      <p class="text-center text-sm text-gray-500 mt-4">
        Don’t have an account? 
        <a href="signup.php" class="text-rose-600 font-medium hover:underline">Sign up</a>
      </p>

    </form>
  </div>

</body>
</html>
