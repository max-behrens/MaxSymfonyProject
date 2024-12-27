<?php
global $userObj;
global $validate;
include "init.php";

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $validate->escape(isset($_POST['email']) ? $_POST['email'] : '');
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validate email
    if (empty($email) || !$validate->filterEmail($email)) {
        $errors[] = "Please enter a valid email address";
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    // If no validation errors, proceed with login attempt
    if (empty($errors)) {
        $user = $userObj->emailExists($email);

        error_log("LOG user: " . print_r($user, true));
        error_log("LOG email: " . print_r($email, true));
        error_log("LOG $user->password: " . print_r($user->password, true));

        if ($user && password_verify($password, $user->password)) {
            $success = true;
            session_start();
            $_SESSION['user_id'] = $user->id;
            $_SESSION['email'] = $user->email;

            // Optionally update hash if needed
            if (password_needs_rehash($user->password, PASSWORD_BCRYPT)) {
                $newHash = $userObj->hash($password);
                // Add method to update password hash
                 $userObj->updatePasswordHash($user->id, $newHash);
            }

            error_log("LOG HERE redirect: " . print_r('', true));

            header("Location: " . BASE_URL . "dashboard.php");
            exit();
        } else {
            // Use a generic error message for security
            $errors[] = "Invalid email or password";
            error_log("Login failed for email: " . $email);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Login</h2>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <p>Login successful!</p>
        </div>
    <?php endif; ?>

    <form method="POST" action="" class="space-y-6">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email"
                   id="email"
                   name="email"
                   value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                   required>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password"
                   id="password"
                   name="password"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                   required>
        </div>

        <div>
            <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Sign in
            </button>
        </div>
    </form>
</div>
</body>
</html>