<?php
include "config.php";

if(isset($_POST['but_submit'])){

    $uname = mysqli_real_escape_string($con,$_POST['txt_uname']);
    $password = mysqli_real_escape_string($con,$_POST['txt_pwd']);

    if ($uname != "" && $password != ""){

        $sql_query = "select count(*) as cntUser from admin where username='".$uname."' and password='".$password."'";
        $result = mysqli_query($con,$sql_query);
        $row = mysqli_fetch_array($result);

        $count = $row['cntUser'];

        if($count > 0){
            $_SESSION['uname'] = $uname;
            header('Location: deletedata.php');
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
  <title>Admin Login – LTI Blood Bank</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-gray-900 flex items-center justify-center px-4">

  <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 relative overflow-hidden">
    
    <!-- Glow -->
    <div class="absolute -top-24 -right-24 w-52 h-52 bg-indigo-400/30 rounded-full blur-3xl"></div>

    <h1 class="text-3xl font-bold text-gray-800 text-center mb-2">
      Admin Panel 🔐
    </h1>
    <p class="text-gray-500 text-center mb-8">
      Secure login to manage donors
    </p>

    <?php if(isset($error)) { ?>
      <div class="mb-4 text-sm text-red-700 bg-red-100 px-4 py-2 rounded-lg">
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
          class="w-full mt-1 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
          placeholder="Admin username"
        />
      </div>

      <div>
        <label class="text-sm text-gray-600">Password</label>
        <input 
          type="password" 
          name="txt_pwd"
          required
          class="w-full mt-1 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
          placeholder="Admin password"
        />
      </div>

      <button 
        type="submit" 
        name="but_submit"
        class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 text-white py-3 rounded-xl font-semibold shadow-lg hover:scale-[1.02] transition-all"
      >
        Login to Admin Panel
      </button>

      <p class="text-center text-sm text-gray-500 mt-4">
        ← Back to 
        <a href="index.php" class="text-indigo-600 font-medium hover:underline">
          User Login
        </a>
      </p>

    </form>
  </div>

</body>
</html>
