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
  <form action="">
    <div>
      <label for="email" class="block text-gray-800 font-bold">Email:</label>
      <input type="text" name="email" id="email" placeholder="@email" class="w-full border border-gray-300 py-2 pl-3 rounded mt-2 outline-none focus:ring-indigo-600 :ring-indigo-600" />
    </div>
    <div class="mb-6">
      <label for="password" class="block text-gray-800 font-bold">Password:</label>
      <input type="password" name="password" id="password" placeholder="password" class="w-full border border-gray-300 py-2 pl-3 rounded mt-2 outline-none focus:ring-indigo-600 :ring-indigo-600" />
      <a href="signUp.php" class="text-sm font-thin text-gray-800 hover:underline mt-2 inline-block hover:text-indigo-600">Create an Account?</a>
    </div>
    <button class="cursor-pointer py-2 px-4 block mt-6 bg-indigo-500 text-white font-bold w-full text-center rounded">Login</button>
  </form>
</div>
</body>
</html>