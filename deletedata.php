<?php
include "config.php";

// Auth guard
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

$conn = new mysqli("mysqldb", "root", "admin123", "customers");
if ($conn->connect_error) die("DB Error");

// Filters
$city = $_GET['city'] ?? '';
$blood = $_GET['blood'] ?? '';

$where = [];
if($city) $where[] = "city LIKE '%".$conn->real_escape_string($city)."%'";
if($blood) $where[] = "bloodgroup = '".$conn->real_escape_string($blood)."'";
$whereSql = count($where) ? "WHERE ".implode(" AND ", $where) : "";

// Delete donor
$deleted = false;
if(isset($_POST['delete_id'])){
    $id = intval($_POST['delete_id']);
    $conn->query("DELETE FROM donors WHERE id=$id");
    $deleted = true;
}

// Stats
$total = $conn->query("SELECT COUNT(*) c FROM donors")->fetch_assoc()['c'];
$aPos = $conn->query("SELECT COUNT(*) c FROM donors WHERE bloodgroup='A_Positive'")->fetch_assoc()['c'];
$today = $conn->query("SELECT COUNT(*) c FROM donors WHERE DATE(bfrom)=CURDATE()")->fetch_assoc()['c'];

// Data
$result = $conn->query("SELECT * FROM donors $whereSql ORDER BY id DESC");

// Export CSV
if(isset($_GET['export']) && $_GET['export'] == '1'){
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="donors.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['First Name','Last Name','Mobile','City','From','To','DOB','Blood Group']);
    while($r = $result->fetch_assoc()){
        fputcsv($out, $r);
    }
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 to-gray-900">

<div class="bg-white shadow">
  <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between">
    <h1 class="font-bold text-indigo-600">🛡 Admin Dashboard</h1>
    <form method="post">
      <button name="but_logout" class="bg-indigo-600 text-white px-4 py-2 rounded">Logout</button>
    </form>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">

  <!-- Stats -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-5 rounded-xl shadow">
      <p class="text-gray-500">Total Donors</p>
      <h2 class="text-2xl font-bold"><?= $total ?></h2>
    </div>
    <div class="bg-white p-5 rounded-xl shadow">
      <p class="text-gray-500">Today Registered</p>
      <h2 class="text-2xl font-bold"><?= $today ?></h2>
    </div>
    <div class="bg-white p-5 rounded-xl shadow">
      <p class="text-gray-500">A+ Donors</p>
      <h2 class="text-2xl font-bold"><?= $aPos ?></h2>
    </div>
  </div>

  <!-- Filters -->
  <form class="bg-white p-4 rounded-xl shadow mb-4 flex gap-3">
    <input name="city" value="<?= htmlspecialchars($city) ?>" placeholder="City" class="border px-3 py-2 rounded w-full">
    <select name="blood" class="border px-3 py-2 rounded">
      <option value="">Blood Group</option>
      <option value="A_Positive">A+</option>
      <option value="B_Positive">B+</option>
      <option value="O_Positive">O+</option>
    </select>
    <button class="bg-indigo-600 text-white px-4 rounded">Filter</button>
    <a href="?export=1" class="bg-gray-200 px-4 py-2 rounded">Export CSV</a>
  </form>

  <!-- Table -->
  <div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="p-2">Name</th>
          <th class="p-2">Mobile</th>
          <th class="p-2">City</th>
          <th class="p-2">Blood</th>
          <th class="p-2 text-right">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr class="border-t">
            <td class="p-2"><?= $row['fname'].' '.$row['lname'] ?></td>
            <td class="p-2"><?= $row['mobileno'] ?></td>
            <td class="p-2"><?= $row['city'] ?></td>
            <td class="p-2 font-semibold"><?= str_replace('_',' ',$row['bloodgroup']) ?></td>
            <td class="p-2 text-right">
              <form method="post" onsubmit="return confirm('Delete donor?')">
                <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                <button class="text-red-600 hover:underline">Delete</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
