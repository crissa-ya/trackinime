<?php
include "config.php";
session_start();

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

$user = $_SESSION["user"];

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM users WHERE id = $id");
    header("Location: admin_users.php");
}

// Handle edit
if (isset($_POST['edit_user'])) {
    $id = $_POST['id'];
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    
    mysqli_query($conn, "UPDATE users SET fullname='$fullname', email='$email', username='$username', role='$role' WHERE id=$id");
    header("Location: admin_users.php");
}

// Fetch all admins
$admins = mysqli_query($conn, "SELECT * FROM users WHERE role='admin' ORDER BY id DESC");

// Fetch all normal users
$normal_users = mysqli_query($conn, "SELECT * FROM users WHERE role='user' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Users</title>
    <style>
        body{
            margin:0;
            font-family:"Poppins",sans-serif;
            background:url('assets/bg.gif') no-repeat center center fixed;
            background-size:cover;
            color:white;
        }
        a{text-decoration:none;color:inherit;}

        /* Sidebar */
        .sidebar{
            position:fixed;
            left:0;
            top:0;
            width:220px;
            height:100%;
            background:rgba(0,0,0,0.85);
            display:flex;
            flex-direction:column;
            align-items:center;
            padding-top:20px;
        }
        .sidebar img.logo{width:80px;margin-bottom:20px;}

        .profile-pic { width:100px;height:100px;border-radius:50%;overflow:hidden;position:relative;margin-bottom:10px;cursor:pointer;background: rgba(255,255,255,0.1);transition:0.2s;}
        .profile-pic:hover { transform: scale(1.05); }
        .profile-pic img { width:100%;height:100%;object-fit:cover;transition:0.3s; }
        .camera-overlay { position:absolute;top:0;left:0;width:100%;height:100%;background: rgba(0,0,0,0.4);display:flex;align-items:center;justify-content:center;opacity:0;transition:0.3s;}
        .profile-pic:hover .camera-overlay { opacity: 1; }
        .camera-overlay svg { width:28px;height:28px;fill:white; }

        .username{font-weight:bold;margin-bottom:20px;}
        .sidebar a.btn{display:block;width:80%;padding:10px;margin-bottom:10px;text-align:center;background: rgba(120,105,255,0.8);border-radius:8px;transition:0.2s;}
        .sidebar a.btn:hover{background: rgba(110,95,245,1);}
        .sidebar a.btn.danger{background: rgba(255,75,75,0.8);}
        .sidebar a.btn.danger:hover{background: rgba(255,60,60,1);}

        .main-content{margin-left:220px;padding:20px 40px;}
        .header{margin-bottom:20px;}
        .header h1{margin:0;font-size:26px;}
        .header .welcome{font-size:20px;margin-top:5px;}
        .header a.about { position:absolute; top:20px; right:40px; padding:8px 15px; background: rgba(120,105,255,0.8); border-radius:6px; color:white; text-decoration:none; transition:0.2s;}
        .header a.about:hover { background: rgba(110,95,245,1); }

        table { width:100%; border-collapse: collapse; margin-top:20px; color:white; }
        th, td { border:1px solid #ddd; padding:10px; text-align:center; }
        th { background-color: rgba(120,105,255,0.9); color:white; }
        tr:nth-child(even) { background-color: rgba(255,255,255,0.1); }

        .user-row { background: rgba(0, 0, 0, 0.5); }
        .user-row td { border: 1px solid rgba(255,255,255,0.2); }

        .btn-action { padding:5px 8px; border-radius:5px; font-size:12px; background: rgba(120,105,255,0.8); color:white; text-decoration:none; transition:0.2s; margin:2px;}
        .btn-action.danger { background: rgba(255,75,75,0.8); }
        .btn-action.view { background: rgba(75,200,255,0.8); }
        .btn-action:hover { opacity:0.8; }

        .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); justify-content:center; align-items:center; }
        .modal-content { background:#111; padding:20px; border-radius:10px; text-align:center; position:relative; color:white; width:350px;}
        .modal-content img.modal-img { width:150px; height:150px; border-radius:50%; object-fit:cover; margin-bottom:15px; }
        .modal-actions button { margin:5px; padding:8px 15px; border:none; border-radius:5px; cursor:pointer; background: #765fff; color:white; transition:0.2s; }
        .modal-actions button:hover { background:#6e5ff5; }
        .close { position:absolute; top:10px; right:15px; font-size:24px; cursor:pointer; }

        .modal-content label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .modal-content input, .modal-content select { 
            width:100%; 
            padding:10px; 
            margin:8px 0; 
            border-radius:5px; 
            border:none; 
            box-sizing: border-box;
        }
        .modal-content button[type=submit] { width:100%; padding:10px; margin-top:10px; background: rgba(120,105,255,0.8); border:none; border-radius:5px; cursor:pointer; color:white; transition:0.2s; }
        .modal-content button[type=submit]:hover { background: rgba(110,95,245,1); }
    </style>
</head>
<body>

<div class="sidebar">
    <a href="admin_dashboard.php"><img src="assets/logo.png" class="logo"></a>

    <!-- Sidebar Profile -->
    <div class="profile-pic" id="profilePic">
        <img src="<?= !empty($user['profile_image']) ? $user['profile_image'] : 'assets/default-avatar.png' ?>" alt="Profile Picture">
        <div class="camera-overlay">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 5a7 7 0 1 0 0 14 7 7 0 0 0 0-14zm9-2h-3.17l-1.84-2H7.01L5.17 3H2v2h20V3z"/>
            </svg>
        </div>
    </div>

    <!-- Profile Modal -->
    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <img src="<?= !empty($user['profile_image']) ? $user['profile_image'] : 'assets/default-avatar.png' ?>" alt="Profile Preview" class="modal-img">
            <div class="modal-actions">
                <button id="changeBtn">Change Profile</button>
                <button id="backBtn">Back</button>
            </div>
            <form id="uploadForm" action="admin_profile.php" method="POST" enctype="multipart/form-data" style="display:none;">
                <input type="file" name="profile_image" id="profileInput">
            </form>
        </div>
    </div>

    <div class="username"><?= htmlspecialchars($user['username']) ?></div>
    <a href="admin_dashboard.php" class="btn">Dashboard</a>
    <a href="admin_profile.php" class="btn">Profile</a>
    <a href="logout.php" class="btn danger">Logout</a>
</div>

<div class="main-content">
    <div class="header">
        <h1>Manage Users</h1>
        <div class="welcome">Hello, <?= htmlspecialchars($user['fullname']) ?></div>
        <a href="admin_about.php" class="about">About Us</a>
    </div>

    <h2>Admins</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th><th>Full Name</th><th>Email</th><th>Username </th><th>Role</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = mysqli_fetch_assoc($admins)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['fullname']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td>
                    <a href="javascript:void(0);" class="btn-action view" onclick="showViewUser('<?= htmlspecialchars($row['fullname'],ENT_QUOTES) ?>','<?= htmlspecialchars($row['email'],ENT_QUOTES) ?>','<?= htmlspecialchars($row['username'],ENT_QUOTES) ?>','<?= $row['role'] ?>')">View</a>
                    <a href="javascript:void(0);" class="btn-action" onclick="showEditForm(<?= $row['id'] ?>,'<?= htmlspecialchars($row['fullname'],ENT_QUOTES) ?>','<?= htmlspecialchars($row['email'],ENT_QUOTES) ?>','<?= htmlspecialchars($row['username'],ENT_QUOTES) ?>','<?= $row['role'] ?>')">Edit</a>
                    <a href="admin_users.php?delete=<?= $row['id'] ?>" class="btn-action danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <h2>Users</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th><th>Full Name</th><th>Email</th><th>Username</th><th>Role</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = mysqli_fetch_assoc($normal_users)): ?>
            <tr class="user-row">
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['fullname']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td>
                    <a href="javascript:void(0);" class="btn-action view" onclick="showViewUser('<?= htmlspecialchars($row['fullname'],ENT_QUOTES) ?>','<?= htmlspecialchars($row['email'],ENT_QUOTES) ?>','<?= htmlspecialchars($row['username'],ENT_QUOTES) ?>','<?= $row['role'] ?>')">View</a>
                    <a href="javascript:void(0);" class="btn-action" onclick="showEditForm(<?= $row['id'] ?>,'<?= htmlspecialchars($row['fullname'],ENT_QUOTES) ?>','<?= htmlspecialchars($row['email'],ENT_QUOTES) ?>','<?= htmlspecialchars($row['username'],ENT_QUOTES) ?>','<?= $row['role'] ?>')">Edit</a>
                    <a href="admin_users.php?delete=<?= $row['id'] ?>" class="btn-action danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- View User Modal -->
<div id="viewModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeViewModal()">&times;</span>
        <h2>User Info</h2>
        <p><strong>Full Name:</strong> <span id="view_fullname"></span></p>
        <p><strong>Email:</strong> <span id="view_email"></span></p>
        <p><strong>Username:</strong> <span id="view_username"></span></p>
        <p><strong>Role:</strong> <span id="view_role"></span></p>
        <a href="javascript:void(0);" class="btn-action" onclick="closeViewModal()">Back</a>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editModal" class="modal">
    <form class="modal-content" method="POST">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Edit User</h2>
        <input type="hidden" name="id" id="edit_id">
        <label>Full Name:</label>
        <input type="text" name="fullname" id="edit_fullname" required>
        <label>Email:</label>
        <input type="email" name="email" id="edit_email" required>
        <label>Username:</label>
        <input type="text" name="username" id="edit_username" required>
        <label>Role:</label>
        <select name="role" id="edit_role" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
        <!-- Buttons container -->
        <div style="display:flex; justify-content:space-between; margin-top:15px; gap:10px;">
            <button type="submit" name="edit_user" class="btn" style="flex:1;">Save Changes</button>
            <button type="submit" name="edit_user" class="btn" style="flex:1;">Back</button>
        </div>




    </form>
</div>

<script>
function showEditForm(id, fullname, email, username, role){
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_fullname').value = fullname;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_role').value = role;
    document.getElementById('editModal').style.display = 'flex';
}
function closeModal(){ document.getElementById('editModal').style.display = 'none'; }
function showViewUser(fullname,email,username,role){
    document.getElementById('view_fullname').innerText = fullname;
    document.getElementById('view_email').innerText = email;
    document.getElementById('view_username').innerText = username;
    document.getElementById('view_role').innerText = role;
    document.getElementById('viewModal').style.display = 'flex';
}
function closeViewModal(){ document.getElementById('viewModal').style.display = 'none'; }

const profilePic = document.getElementById('profilePic');
const profileModal = document.getElementById('profileModal');
const closeProfile = profileModal.querySelector('.close');
const changeBtn = document.getElementById('changeBtn');
const backBtn = document.getElementById('backBtn');
const profileInput = document.getElementById('profileInput');
const uploadForm = document.getElementById('uploadForm');

profilePic.addEventListener('click', () => { profileModal.style.display = 'flex'; });
closeProfile.addEventListener('click', () => { profileModal.style.display = 'none'; });
backBtn.addEventListener('click', () => { profileModal.style.display = 'none'; });
changeBtn.addEventListener('click', () => { profileInput.click(); });
profileInput.addEventListener('change', () => { uploadForm.submit(); });


</script>
</body>
</html>
