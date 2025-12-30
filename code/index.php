<?php 
session_start();

// 1. Security: Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'connect.php';
$user_id = $_SESSION['user_id'];

// --- LOGIC: ADD DETAILS (Create) ---
if (isset($_POST['addStudent'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $address = $_POST['address'];

    // Using Prepared Statements for Security
    $stmt = $conn->prepare("INSERT INTO students (student_name, age, address, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sisi", $name, $age, $address, $user_id);

    if ($stmt->execute()) {
        // Refresh page to show new data
        header("Location: index.php");
        exit();
    } else {
        $error = "Error adding student.";
    }
    $stmt->close();
}

// --- LOGIC: DELETE DETAILS ---
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Secure delete (ensures user can only delete their own data)
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $delete_id, $user_id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Student Portal</title>
    <link rel="stylesheet" href="../style/home.css">
</head>
<body>

    <nav class="navBar">
        <div class="navBrand">STUDENT<span>PORTAL</span></div>
        <div class="navUser">
            <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
            <a href="logout.php" class="logoutBtn">Logout</a>
        </div>
    </nav>

    <div class="dashboard-container">
        
        <aside class="registration-sidebar">
            <div class="glassCard">
                <h3 class="cardTitle">Add Details</h3>
                <p class="cardSubtitle">Register a new student below</p>
                
                <form action="index.php" method="POST" class="verticalForm">
                    <div class="inputBox">
                        <label>Full Name</label>
                        <input type="text" name="name" placeholder="Full Name" required>
                    </div>
                    
                    <div class="inputBox">
                        <label>Age</label>
                        <input type="number" name="age" placeholder="Age" required>
                    </div>
                    
                    <div class="inputBox">
                        <label>Address</label>
                        <textarea name="address" placeholder="Enter Full Address" required></textarea>
                    </div>

                    <button type="submit" name="addStudent" class="submitBtn">Save Student</button>
                </form>
            </div>
        </aside>

        <section class="table-section">
            <div class="glassCard">
                <div class="tableHeader">
                    <h2 class="sectionTitle">Student List</h2>
                    <?php 
                        // Count total students for this user
                        $countQuery = $conn->prepare("SELECT id FROM students WHERE user_id = ?");
                        $countQuery->bind_param("i", $user_id);
                        $countQuery->execute();
                        $countQuery->store_result();
                        $count = $countQuery->num_rows;
                        $countQuery->close();
                    ?>
                    <span class="countBadge"><?php echo $count; ?> Records Found</span>
                </div>
                
                <div class="tableWrapper">
                    <table class="styledTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch data securely
                            $stmt = $conn->prepare("SELECT * FROM students WHERE user_id = ? ORDER BY id DESC");
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if($result->num_rows > 0):
                                while ($row = $result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['student_name']); ?></strong></td>
                                <td><span class="agePill"><?php echo $row['age']; ?></span></td>
                                <td class="truncateText"><?php echo htmlspecialchars($row['address']); ?></td>
                                <td>
                                    <div class="btnGroup">
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="editIcon">Edit</a>
                                        <a href="index.php?delete=<?php echo $row['id']; ?>" class="deleteIcon" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                                <tr><td colspan="4" class="noData">No details added yet. Use the form to add one!</td></tr>
                            <?php endif; 
                            $stmt->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</body>
</html>