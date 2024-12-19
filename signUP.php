<?php
include('connexion.php');

$username = $email = $password = $message = $successMessage = $errorMessage = '';

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    if ($password !== $confirmPassword) {
        $errorMessage = "Passwords do not match!";
    } else {
        $hashedPassword = md5($password);

        $stmt = $conn->prepare("INSERT INTO author (name_author, email, password_auth) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            $successMessage = "Registration successful!";
            header("Location: login.php");
            exit;
        } else {
            $errorMessage = "Something went wrong. Please try again!";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>SignUp-page</title>
</head>
<body class="bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Create an Account</h2>

        <?php if ($errorMessage): ?>
            <div class="text-red-500 text-center mb-4">
                <strong><?php echo $errorMessage; ?></strong>
            </div>
        <?php endif; ?>
        
        <?php if ($successMessage): ?>
            <div class="text-green-500 text-center mb-4">
                <strong><?php echo $successMessage; ?></strong>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="mb-4">
                <label for="username" class="block text-gray-700 font-semibold mb-2">Username</label>
                <input type="text" id="username" name="username" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Enter your username" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Enter your password" required>
            </div>
            <div class="mb-4">
                <label for="confirm-password" class="block text-gray-700 font-semibold mb-2">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Confirm your password" required>
            </div>
            <button type="submit" name="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg font-semibold hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50">Register</button>
        </form>
        <p class="text-center text-gray-600 mt-4">Already have an account? <a href="login.php" class="text-blue-500 font-semibold">Sign In</a></p>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            let isValid = true;

            document.querySelectorAll('.text-red-500').forEach(function(element) {
                element.classList.add('hidden');
            });

            const username = document.getElementById('username').value;
            if (!username) {
                document.getElementById('usernameError').classList.remove('hidden');
                isValid = false;
            }

            const email = document.getElementById('email').value;
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!email || !emailPattern.test(email)) {
                document.getElementById('emailError').classList.remove('hidden');
                isValid = false;
            }

            const password = document.getElementById('password').value;
            if (!password) {
                document.getElementById('passwordError').classList.remove('hidden');
                isValid = false;
            }

            const confirmPassword = document.getElementById('confirm-password').value;
            if (password !== confirmPassword) {
                document.getElementById('confirmPasswordError').classList.remove('hidden');
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
