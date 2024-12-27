<?php
global $userObj;
include "init.php";

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
//if (!isset($_SESSION['user_id'])) {
//    header("Location: " . BASE_URL . "login.php");
//    exit();
//}

// Get all users
$users = $userObj->getAllUsers();

error_log("DASHBOARD users: " . print_r($users, true));


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
        html.dark {
            background-color: #1a202c; /* Dark background */
            color: #edf2f7; /* Light text */
        }

        html.dark .bg-gray-100 {
            background-color: #1a202c;
        }

        html.dark .text-gray-800 {
            color: #edf2f7;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 dark:text-gray-200 min-h-screen">
<div class="flex items-center justify-end">
    <button id="theme-toggle" class="px-4 py-2 rounded text-sm font-medium bg-gray-200 dark:bg-gray-700 dark:text-white">
        Toggle Dark Mode
    </button>
</div>
<nav class="bg-white dark:bg-gray-800 shadow-lg">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="text-xl font-semibold text-gray-800 dark:text-white">
                Dashboard
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?></span>
                <a href="logout.php" class="text-red-600 hover:text-red-800">Logout</a>
            </div>
        </div>
    </div>
</nav>

<main class="max-w-6xl mx-auto mt-8 px-4">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Users</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo htmlspecialchars($user->userId); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <?php echo htmlspecialchars(isset($user->fullName) ? $user->fullName : 'N/A'); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo htmlspecialchars($user->email); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
</body>
</html>


<script>
    // Select the button and HTML element
    const themeToggle = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement;

    // Load the theme from localStorage
    const currentTheme = localStorage.getItem('theme') || 'light';
    if (currentTheme === 'dark') {
        htmlElement.classList.add('dark');
    }

    // Toggle theme on button click
    themeToggle.addEventListener('click', () => {
        if (htmlElement.classList.contains('dark')) {
            htmlElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            htmlElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    });
</script>