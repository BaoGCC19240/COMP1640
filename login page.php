<!DOCTYPE html>
<html>

<head>
    <title>G2Edu</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Login To G2Edu</h1>
    <?php
    session_start();
    if (isset($_POST['submit'])) {
        $us = $_POST['username'];
        $pa = $_POST['password'];
        include_once("connection.php");
        // Escape special characters in a string
        $us = mysqli_real_escape_string($conn, $us);
        $pa = mysqli_real_escape_string($conn, $pa);
        $encrypted_password = password_hash($pa, PASSWORD_DEFAULT);
        $sq = "select * from users where email='$us'";
        $res = mysqli_query($conn, $sq) or die(mysqli_error($conn));
        $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
        if (mysqli_num_rows($res) == 1) {
            $encrypted_password = $row['Password'];
            if (password_verify($pa, $encrypted_password)) {

                $_SESSION['depart'] = $row['DepartmentID'];
                $_SESSION["email"] = stripslashes($us);
                $_SESSION["us_id"] = $row['UserID'];
                header("Location:index.php");
            } else {

                echo "Password is incorrect, please login again";
            }
        } else {

            echo "Username or password is incorrect, please login again";
        }
    }
    ?>

    <form method="post" action="">
        <label>Email:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" name="submit" value="Login">
    </form>
</body>

</html>