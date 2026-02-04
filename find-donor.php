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

    public function getdata() {
        $sql = "select * from donors order by id desc";
        return mysqli_query($this->con,$sql);
    }
}

$script = new script();
$result = $script->getdata();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Find Donor – Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-red-500 via-rose-500 to-pink-600">

  <!-- Navbar -->
  <div class="bg-white/90 backdrop-blur shadow-md">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
      <h1 class="text-xl font-bold text-rose-600">🩸 LTI Blood Bank</h1>

      <div class="flex items-center gap-3">
        <a href="donate-blood.php" class="text-sm text-gray-600 hover:text-rose-600">Donate</a>
        <a href="search.php" class="text-sm text-gray-600 hover:text-rose-600">Advanced Search</a>

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
  </div>

  <!-- Main -->
  <div class="max-w-7xl mx-auto px-4 py-10">

    <div class="bg-white rounded-2xl shadow-2xl p-6 relative overflow-hidden">
      <div class="absolute -top-24 -right-24 w-52 h-52 bg-pink-400/30 rounded-full blur-3xl"></div>

      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
          <h2 class="text-3xl font-bold text-gray-800">Find Donors 🔍</h2>
          <p class="text-gray-500">Browse available donors</p>
        </div>

        <div class="flex gap-3">
          <a href="search.php" class="bg-gray-100 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">
            Advanced Search
          </a>
          <a href="index.html" class="bg-gray-100 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">
            Home
          </a>
        </div>
      </div>

      <div class="overflow-x-auto rounded-xl border">
        <table class="min-w-full text-sm text-left">
          <thead class="bg-gray-50 sticky top-0">
            <tr class="text-gray-600">
              <th class="px-4 py-3">First Name</th>
              <th class="px-4 py-3">Last Name</th>
              <th class="px-4 py-3">Mobile</th>
              <th class="px-4 py-3">City</th>
              <th class="px-4 py-3">Available From</th>
              <th class="px-4 py-3">Available To</th>
              <th class="px-4 py-3">DOB</th>
              <th class="px-4 py-3">Blood Group</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <?php if($result && mysqli_num_rows($result) > 0): ?>
              <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr class="hover:bg-rose-50 transition">
                  <td class="px-4 py-2"><?= htmlspecialchars($row['fname']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['lname']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['mobileno']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['city']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['bfrom']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['bto']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['dob']) ?></td>
                  <td class="px-4 py-2 font-semibold text-rose-600">
                    <?= htmlspecialchars(str_replace('_',' ', $row['bloodgroup'])) ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center py-6 text-gray-500">
                  No donors found.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>

</body>
</html>
