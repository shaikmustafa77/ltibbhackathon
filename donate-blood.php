<?php
include "config.php";

// Auth guard
if(!isset($_SESSION['uname'])){
    header('Location: index.php');
    exit;
}

// Logout
if(isset($_POST['but_logout'])){
    session_destroy();
    header('Location: index.php');
    exit;
}

class script {
    public $con;

    public function __construct(){
        $server = "mysqldb";
        $user = "root";
        $pass = "admin123";
        $db = "customers";
        $this->con = mysqli_connect($server,$user,$pass,$db) or die("unable to connect");
    }

    public function add($fname,$lname,$mobileno,$city,$bfrom,$bto,$dob,$bloodgroup){
        $sql = "insert into donors(fname,lname,mobileno,city,bfrom,bto,dob,bloodgroup) 
                values('".urlencode($fname)."','".urlencode($lname)."','".urlencode($mobileno)."','".urlencode($city)."',
                       '".urlencode($bfrom)."','".urlencode($bto)."','".urlencode($dob)."','".urlencode($bloodgroup)."')";
        return mysqli_query($this->con,$sql);
    }
}

$success = false;
$error = false;

$script = new script();
if(isset($_POST['sub'])){
    $success = $script->add(
        $_POST['fname'],
        $_POST['lname'],
        $_POST['mobileno'],
        $_POST['city'],
        $_POST['bfrom'],
        $_POST['bto'],
        $_POST['dob'],
        $_POST['bloodgroup']
    );
    if(!$success) $error = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Donate Blood – Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-red-500 via-rose-500 to-pink-600">

  <!-- Navbar -->
  <div class="bg-white/90 backdrop-blur shadow-md">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
      <h1 class="text-xl font-bold text-rose-600">🩸 LTI Blood Bank</h1>

      <form method="post">
        <button 
          name="but_logout"
          class="bg-rose-500 text-white px-4 py-2 rounded-lg hover:bg-rose-600 transition"
        >
          Logout
        </button>
      </form>
    </div>
  </div>

  <!-- Main -->
  <div class="max-w-5xl mx-auto px-4 py-10">

    <div class="bg-white rounded-2xl shadow-2xl p-8 relative overflow-hidden">
      <div class="absolute -top-24 -right-24 w-52 h-52 bg-pink-400/30 rounded-full blur-3xl"></div>

      <h2 class="text-3xl font-bold text-gray-800 mb-2">Register as a Donor ❤️</h2>
      <p class="text-gray-500 mb-8">Your donation can save lives.</p>

      <?php if($success): ?>
        <div class="mb-4 text-sm text-green-700 bg-green-100 px-4 py-2 rounded-lg">
          Donor registered successfully 🎉
        </div>
      <?php endif; ?>

      <?php if($error): ?>
        <div class="mb-4 text-sm text-red-700 bg-red-100 px-4 py-2 rounded-lg">
          Something went wrong. Please try again.
        </div>
      <?php endif; ?>

      <form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div>
          <label class="text-sm text-gray-600">First Name</label>
          <input type="text" name="fname" required
            class="w-full mt-1 px-4 py-3 rounded-xl border focus:ring-2 focus:ring-rose-500 focus:outline-none"/>
        </div>

        <div>
          <label class="text-sm text-gray-600">Last Name</label>
          <input type="text" name="lname" required
            class="w-full mt-1 px-4 py-3 rounded-xl border focus:ring-2 focus:ring-rose-500 focus:outline-none"/>
        </div>

        <div>
          <label class="text-sm text-gray-600">Mobile Number</label>
          <input type="number" name="mobileno" maxlength="10" required
            class="w-full mt-1 px-4 py-3 rounded-xl border focus:ring-2 focus:ring-rose-500 focus:outline-none"/>
        </div>

        <div>
          <label class="text-sm text-gray-600">City</label>
          <input type="text" name="city" required
            class="w-full mt-1 px-4 py-3 rounded-xl border focus:ring-2 focus:ring-rose-500 focus:outline-none"/>
        </div>

        <div>
          <label class="text-sm text-gray-600">Available From</label>
          <input type="date" name="bfrom" required
            class="w-full mt-1 px-4 py-3 rounded-xl border focus:ring-2 focus:ring-rose-500 focus:outline-none"/>
        </div>

        <div>
          <label class="text-sm text-gray-600">Available To</label>
          <input type="date" name="bto" required
            class="w-full mt-1 px-4 py-3 rounded-xl border focus:ring-2 focus:ring-rose-500 focus:outline-none"/>
        </div>

        <div>
          <label class="text-sm text-gray-600">Date of Birth (18+)</label>
          <input 
            type="date" 
            name="dob" 
            max="<?php $d=strtotime('-18 Years'); echo date('Y-m-d', $d); ?>" 
            required
            class="w-full mt-1 px-4 py-3 rounded-xl border focus:ring-2 focus:ring-rose-500 focus:outline-none"
          />
        </div>

        <div>
          <label class="text-sm text-gray-600">Blood Group</label>
          <select name="bloodgroup" required
            class="w-full mt-1 px-4 py-3 rounded-xl border focus:ring-2 focus:ring-rose-500 focus:outline-none">
            <option value="">Select</option>
            <option value="AB_Positive">AB Positive</option>
            <option value="AB_Negative">AB Negative</option>
            <option value="A_Positive">A Positive</option>
            <option value="A_Negative">A Negative</option>
            <option value="B_Positive">B Positive</option>
            <option value="B_Negative">B Negative</option>
            <option value="O_Positive">O Positive</option>
            <option value="O_Negative">O Negative</option>
          </select>
        </div>

        <div class="md:col-span-2 pt-4">
          <button 
            type="submit" 
            name="sub"
            class="w-full bg-gradient-to-r from-rose-500 to-pink-600 text-white py-3 rounded-xl font-semibold shadow-lg hover:scale-[1.02] transition-all"
          >
            Register as Donor
          </button>
        </div>

      </form>
    </div>
  </div>

</body>
</html>
