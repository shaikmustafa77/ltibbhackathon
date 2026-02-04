<?php
class script{

    public $con;

    public function __construct(){
        $server = "mysqldb";
        $user = "root";
        $pass = "admin123";
        $db = "customers";

        $this->con = mysqli_connect($server,$user,$pass,$db) or die("unable to connect");
    }

    public function add($username,$name,$password){
        $sql = "insert into users(username,name,password) values('".urlencode($username)."','".urlencode($name)."','".urlencode($name)."')";
        $res = mysqli_query($this->con,$sql);

        if($res) {
            return true;
        } else {
            return false;
        }
    }
}

$success = false;
$error = false;

$script = new script();
if(isset($_POST['sub'])){
    $success = $script->add($_POST['username'], $_POST['name'], $_POST['password']);
    if(!$success) $error = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Sign Up – Blood Donation App</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-rose-500 via-pink-500 to-red-600 flex items-center justify-center px-4">

  <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl p-8 relative overflow-hidden">

    <!-- Glow -->
    <div class="absolute -top-20 -right-20 w-40 h-40 bg-pink-400 opacity-30 rounded-full blur-3xl"></div>

    <h1 class="text-3xl font-bold text-gray-800 text-center mb-2">
      Create Account 🚀
    </h1>
    <p class="text-gray-500 text-center mb-8">
      Join the Blood Donation Platform
    </p>

    <?php if($success): ?>
      <div class="mb-4 text-sm text-green-700 bg-green-100 px-4 py-2 rounded-lg">
        Account created successfully!  
        <a href="index.php" class="font-semibold underline">Login now</a>
      </div>
    <?php endif; ?>

    <?php if($error): ?>
      <div class="mb-4 text-sm text-red-700 bg-red-100 px-4 py-2 rounded-lg">
        Something went wrong. Please try again.
      </div>
    <?php endif; ?>

    <form method="post" class="space-y-5">

      <div>
        <label class="text-sm text-gray-600">Username</label>
        <input 
          type="text" 
          name="username"
          required
          class="w-full mt-1 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-rose-500 focus:outline-none"
          placeholder="Choose a username"
        />
      </div>

      <div>
        <label class="text-sm text-gray-600">Full Name</label>
        <input 
          type="text" 
          name="name"
          required
          class="w-full mt-1 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-rose-500 focus:outline-none"
          placeholder="Your full name"
        />
      </div>

      <div>
        <label class="text-sm text-gray-600">Password</label>
        <input 
          type="password" 
          name="password"
          required
          class="w-full mt-1 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-rose-500 focus:outline-none"
          placeholder="Create a password"
        />
      </div>

      <button 
        type="submit" 
        name="sub"
        class="w-full bg-gradient-to-r from-rose-500 to-pink-600 text-white py-3 rounded-xl font-semibold shadow-lg hover:scale-[1.02] transition-all"
      >
        Create Account
      </button>

      <p class="text-center text-sm text-gray-500 mt-4">
        Already have an account? 
        <a href="index.php" class="text-rose-600 font-medium hover:underline">Login</a>
      </p>

    </form>

    <div class="mt-6 text-center">
      <a href="index.html" class="inline-block text-sm text-gray-400 hover:text-gray-600">
        ← Back to Home
      </a>
    </div>

  </div>

</body>
</html>
