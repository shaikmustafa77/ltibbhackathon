<?php
include "config.php";

// Auth guard (admin)
if(!isset($_SESSION['uname'])){
    header('Location: indexadmin.php');
    exit;
}

// Logout
if(isset($_POST['but_logout'])){
    session_destroy();
    header('Location: indexadmin.php');
    exit;
}

$servername = "mysqldb";
$username = "root";
$password = "admin123";
$dbname = "customers";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Delete donor
$deleted = false;
if(isset($_POST['delete_id'])){
    $id = intval($_POST['delete_id']);
    $conn->query("DELETE FROM donors WHERE id = $id");
    $deleted = true;
}

// Fetch donors
$result = $conn->query("SELECT * FROM donors ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard – Manage Donors</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-gray-900">

  <!-- Navbar -->
  <div class="bg-white/90 backdrop-blur shadow-md">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
      <h1 class="text-xl font-bold text-indigo-600">🛡️ Admin Panel</h1>

      <form method="post">
        <button 
          name="but_logout"
          class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition"
        >
          Logout
        </button>
      </form>
    </div>
  </div>

  <!-- Main -->
  <div class="max-w-7xl mx-auto px-4 py-10">

    <div class="bg-white rounded-2xl shadow-2xl p-6 relative overflow-hidden">
      <div class="absolute -top-24 -right-24 w-52 h-52 bg-indigo-400/30 rounded-full blur-3xl"></div>

      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
          <h2 class="text-3xl font-bold text-gray-800">Manage Donors 🧾</h2>
          <p class="text-gray-500">View & delete donor records</p>
        </div>

        <div class="flex gap-3">
          <a href="find-donor.php" class="bg-gray-100 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">
            View as User
          </a>
        </div>
      </div>

      <?php if($deleted): ?>
        <div class="mb-4 text-sm text-green-700 bg-green-100 px-4 py-2 rounded-lg">
          Donor deleted successfully.
        </div>
      <?php endif; ?>

      <div class="overflow-x-auto rounded-xl border">
        <table class="min-w-full text-sm text-left">
          <thead class="bg-gray-50 sticky top-0">
            <tr class="text-gray-600">
              <th class="px-4 py-3">Name</th>
              <th class="px-4 py-3">Mobile</th>
              <th class="px-4 py-3">City</th>
              <th class="px-4 py-3">From</th>
              <th class="px-4 py-3">To</th>
              <th class="px-4 py-3">DOB</th>
              <th class="px-4 py-3">Blood Group</th>
              <th class="px-4 py-3 text-right">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <?php if($result && $result->num_rows > 0): ?>
              <?php while($row = $result->fetch_assoc()): ?>
                <tr class="hover:bg-indigo-50 transition">
                  <td class="px-4 py-2 font-medium">
                    <?= htmlspecialchars($row['fname'].' '.$row['lname']) ?>
                  </td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['mobileno']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['city']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['bfrom']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['bto']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['dob']) ?></td>
                  <td class="px-4 py-2 font-semibold text-indigo-600">
                    <?= htmlspecialchars(str_replace('_',' ', $row['bloodgroup'])) ?>
                  </td>
                  <td class="px-4 py-2 text-right">
                    <form method="post" onsubmit="return confirm('Delete this donor? This action cannot be undone.');">
                      <input type="hidden" name="delete_id" value="<?= $row['id'] ?>" />
                      <button class="text-red-600 hover:text-red-800 font-medium">
                        Delete
                      </button>
                    </form>
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
