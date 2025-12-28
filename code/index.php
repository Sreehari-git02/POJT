<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'connect.php';
$user_id = $_SESSION['user_id'];
if (isset($_POST['addStudent'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $address = $_POST['address'];

    $stmt = $conn->prepare(
        "INSERT INTO students (student_name, age, address, user_id)
        VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("sisi", $name, $age, $address, $user_id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error inserting data";
    }
}
// DELETE STUDENT
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Secure delete
    $stmt = $conn->prepare(
        "DELETE FROM students WHERE id = ? AND user_id = ?"
    );
    $stmt->bind_param("ii", $delete_id, $user_id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting record";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Student Portal</title>
    <link rel="stylesheet" href="../style/home.css">
</head>
<body>
    <nav class="navBar">
        <div class="navBrand">STUDENT<span>PORTAL</span></div>
        <div class="navUser">
            <span>Welcome, <strong><?php echo $_SESSION['username']; ?></strong></span>
            <a href="logout.php" class="logoutBtn">Logout</a>
        </div>
    </nav>

    <div class="dashboard-container">
        <aside class="registration-sidebar">
            <div class="glassCard">
                <h3 class="cardTitle">New Registration</h3>
                <form action="index.php" method="POST" class="verticalForm">
                    <div class="inputBox">
                        <label>Full Name</label>
                        <input type="text" name="name" placeholder="Enter name" required>
                    </div>
                    <div class="inputBox">
                        <label>Age</label>
                        <input type="number" name="age" placeholder="Enter age" required>
                    </div>
                    <div class="inputBox">
                        <label>Address</label>
                        <textarea name="address" placeholder="Enter full address" required></textarea>
                    </div>
                    <button type="submit" name="addStudent" class="submitBtn">Register Student</button>
                </form>
            </div>
        </aside>

        <section class="table-section">
            <div class="glassCard">
                <div class="tableHeader">
                    <h2 class="sectionTitle">Student Database</h2>
                    <?php 
                        $count = $conn->query("SELECT id FROM students WHERE user_id = $user_id")->num_rows;
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
                            $result = $conn->query("SELECT * FROM students WHERE user_id = $user_id ORDER BY id DESC");
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
                                        <a href="index.php?delete=<?php echo $row['id']; ?>" class="deleteIcon" onclick="return confirm('Delete record?')">Delete</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                                <tr><td colspan="4" class="noData">No students registered yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</body>
</html>