<?php
 
include('connexion.php');

$email = $password = $errorMessage = '';

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $hashed_password = md5($password);

    $stmt = $conn->prepare("SELECT * FROM author WHERE email = ? AND password_auth = ?");
    $stmt->bind_param("ss", $email, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $author = $result->fetch_assoc();

        $_SESSION['Author_id'] = $author['Author_id'];
        $_SESSION['name_author'] = $author['name_author'];

        header("Location: admin.php");
        exit();
    } else {
        $errorMessage = "Invalid email or password.";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login-page</title>
</head>
<body class="bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center min-h-screen">
    <div class="max-w-xl py-6 px-8 h-80 mt-20 bg-white rounded shadow-xl">
        <h2 class="text-2xl font-bold text-center mb-4 text-gray-800">Login</h2>

        <?php if ($errorMessage): ?>
            <div class="text-red-500 text-center mb-4">
                <strong><?php echo $errorMessage; ?></strong>
            </div>
        <?php endif; ?>


        <form action="" method="POST">
            <div>
                <label for="email" class="block text-gray-800 font-bold">Email:</label>
                <input type="text" name="email" id="email" placeholder="Enter your email" class="w-full border border-gray-300 py-2 pl-3 rounded mt-2 outline-none focus:ring-indigo-600 :ring-indigo-600" required />
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-800 font-bold">Password:</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" class="w-full border border-gray-300 py-2 pl-3 rounded mt-2 outline-none focus:ring-indigo-600 :ring-indigo-600" required />
                <a href="signUp.php" class="text-sm font-thin text-gray-800 hover:underline mt-2 inline-block hover:text-indigo-600">Create an Account?</a>
            </div>
            <button type="submit" name="submit" class="cursor-pointer py-2 px-4 block mt-6 bg-indigo-500 text-white font-bold w-full text-center rounded">Login</button>
        </form>
    </div>
</body>
</html>
