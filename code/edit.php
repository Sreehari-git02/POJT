<?php
session_start();
include 'connect.php';

// 1. Security Check: Redirect if not logged in
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$user_id = $_SESSION['user_id'];

// 2. Fetch the Student Record to Edit
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if (!$student) {
        die("Record not found or access denied.");
    }
} else {
    header("Location: index.php");
    exit();
}

// 3. Handle the Update Logic (POST)
if (isset($_POST['updateStudent'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $address = $_POST['address'];

    $updateStmt = $conn->prepare("UPDATE students SET student_name = ?, age = ?, address = ? WHERE id = ? AND user_id = ?");
    $updateStmt->bind_param("sisii", $name, $age, $address, $id, $user_id);
    
    if ($updateStmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Student Portal</title>
    <link rel="stylesheet" href="../style/home.css">
</head>
<body>
    <nav class="navBar">
        <div class="navBrand">STUDENT<span>PORTAL</span></div>
        <ul class="navLinks">
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="logout.php" class="logoutLink">Logout</a></li>
        </ul>
    </nav>

    <main class="mainContent">
        <div class="glassCard">
            <h2 class="sectionTitle">Update Student Details</h2>
            
            <form action="edit.php?id=<?php echo $id; ?>" method="POST" class="studentForm">
                <div class="formGrid">
                    <div class="inputGroup">
                        <label style="display:block; margin-bottom:5px; font-size:0.8rem; opacity:0.7;">Full Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($student['student_name']); ?>" required>
                    </div>
                    <div class="inputGroup">
                        <label style="display:block; margin-bottom:5px; font-size:0.8rem; opacity:0.7;">Age</label>
                        <input type="number" name="age" value="<?php echo $student['age']; ?>" required>
                    </div>
                </div>
                
                <div class="inputGroup">
                    <label style="display:block; margin-bottom:5px; font-size:0.8rem; opacity:0.7;">Address</label>
                    <textarea name="address" required><?php echo htmlspecialchars($student['address']); ?></textarea>
                </div>

                <button type="submit" name="updateStudent" class="primaryBtn">Save Changes</button>
                <a href="index.php" style="display:block; text-align:center; margin-top:15px; color:#a0aec0; text-decoration:none; font-size:0.9rem;">Cancel and Go Back</a>
            </form>
        </div>
    </main>
</body>
</html>