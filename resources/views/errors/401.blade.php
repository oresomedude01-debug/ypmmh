<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-cyan-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center">
        <div class="mb-8">
            <h1 class="text-6xl font-bold text-blue-600 mb-4">401</h1>
            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Unauthorized</h2>
            <p class="text-gray-600 mb-6">You need to log in to access this resource.</p>
        </div>

        <div class="space-y-3">
            <a href="/login" class="inline-block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                Go to Login
            </a>
            <a href="/" class="inline-block w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 px-6 rounded-lg transition duration-200">
                Go to Home
            </a>
        </div>

        <div class="mt-8 text-sm text-gray-500">
            <p>Don't have an account? <a href="/register" class="text-blue-600 hover:underline">Register here</a></p>
        </div>
    </div>
</body>
</html>
