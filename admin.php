<?php 
session_start();
include ("connexion.php");


if (!isset($_SESSION['Author_id'])) {
    header("Location: login.php");
    exit;
}


$query = "SELECT * FROM article WHERE Author_id = " . $_SESSION['Author_id'];
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

if (isset($_POST['SubBtn'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $id = $_SESSION['Author_id'];

    $sql = "INSERT INTO article (Author_id, title, content) 
            VALUES ('$id', '$title', '$content')";

        if ($conn->query($sql) === TRUE) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
        }

        if (isset($_POST['delete'])) {
            $titleToDelete = $_POST['titleToDelete'];
    
            if (!empty($titleToDelete)) {
                $stmt = $conn->prepare("DELETE FROM article WHERE title = ?");
                $stmt->bind_param("s", $titleToDelete);
    
                if ($stmt->execute()) {
                    echo "<script>alert('Article deleted successfully!'); window.location.href = '" . $_SERVER['PHP_SELF'] . "';</script>";
                } else {
                    echo "<script>alert('Error deleting article');</script>";
                }
    
                $stmt->close();
            } else {
                echo "<script>alert('Please provide a title to delete');</script>";
            }
        }
    
        $resultat = $conn->query("SELECT title FROM article WHERE Author_id = " . $_SESSION['Author_id']);
        
        $total_art_result = $conn->query('SELECT COUNT(id_art) FROM article WHERE Author_id = ' . $_SESSION['Author_id']);
        $total_art = $total_art_result->fetch_assoc();
        $total_art = $total_art['COUNT(id_art)'];

        $total_vues_result = $conn->query('SELECT SUM(views) as total_views FROM article WHERE Author_id = ' . $_SESSION['Author_id']);
        $total_vues = $total_vues_result->fetch_assoc();
        $total_vues = $total_vues['total_views'];

        $total_comt_result = $conn->query('SELECT COUNT(*) as total FROM Comment WHERE id_art IN (SELECT id_art FROM article WHERE Author_id = ' . $_SESSION['Author_id'] . ')');
        $total_comt = $total_comt_result->fetch_assoc();
        $total_comt = $total_comt['total'];

        $total_likes_result = $conn->query('SELECT SUM(likes) as total_likes FROM article WHERE Author_id = ' . $_SESSION['Author_id']);
        $total_likes = $total_likes_result->fetch_assoc();
        $total_likes = $total_likes['total_likes'];



        if (isset($_POST['update'])) {
            $updatedTitle = $_POST['updatedTitle'];
            $updatedContent = $_POST['updatedContent'];
            $originalTitle = $_POST['originalTitle'];
        
            $stmt = $conn->prepare("UPDATE article SET title = ?, content = ? WHERE title = ?");
            $stmt->bind_param("sss", $updatedTitle, $updatedContent, $originalTitle);
        
            if ($stmt->execute()) {
                echo "<script>alert('Article updated successfully!'); window.location.href = '" . $_SERVER['PHP_SELF'] . "';</script>";
            } else {
                echo "<script>alert('Error updating article');</script>";
            }
            $stmt->close();
        }

        
        if(isset($_POST['comment_id'])) {
            $comment_id = $_POST['comment_id'];
            
            $stmt = $conn->prepare("DELETE FROM Comment WHERE cmt_id = ?");
            $stmt->bind_param("i", $comment_id);
            
            if($stmt->execute()) {
                header("Location: admin.php");
                exit;
            } else {
                echo "Error deleting comment";
            }
        }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap"
        rel="stylesheet" />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tw-elements/css/tw-elements.min.css" />
    <script src="https://cdn.tailwindcss.com/3.3.0"></script>
    <script>
    tailwind.config = {
        darkMode: "class",
        theme: {
            fontFamily: {
                sans: ["Roboto", "sans-serif"],
                body: ["Roboto", "sans-serif"],
                mono: ["ui-monospace", "monospace"],
                poppins: ['Poppins', 'sans-serif'],
            },
        },
        corePlugins: {
            preflight: false,
        },
    };
    </script>
    <title>Home</title>
</head>
<body class="font-poppins flex">
   
    <nav id="sidebar" class="bg-[#056EE6] h-screen sticky top-0 left-0 py-6 font-[sans-serif] overflow-auto">
        <div class="flex flex-col items-center cursor-pointer px-4" onclick="window.location.href = 'index.php'">
          <img src='images/logo.jpg' class="w-12 h-12 rounded-full border-2 border-white" />
          <div class="mt-2 text-center">
            <p class="text-sm text-white mt-2"><?php echo $_SESSION['name_author'] ; ?></p>
            <p class="text-xs text-gray-300 mt-0.5">Admin</p>
          </div>
        </div>
      
        <ul class="space-y-8 mt-20">
          <li>
            <a href="javascript:void(0)" id="dash-btn" class="text-white text-sm flex flex-col items-center hover:bg-[#0C62C5] rounded px-4 py-5 transition-all">
              <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 mb-3" viewBox="0 0 512 512">
                <path d="M197.332 170.668h-160C16.746 170.668 0 153.922 0 133.332v-96C0 16.746 16.746 0 37.332 0h160c20.59 0 37.336 16.746 37.336 37.332v96c0 20.59-16.746 37.336-37.336 37.336zM37.332 32A5.336 5.336 0 0 0 32 37.332v96a5.337 5.337 0 0 0 5.332 5.336h160a5.338 5.338 0 0 0 5.336-5.336v-96A5.337 5.337 0 0 0 197.332 32zm160 480h-160C16.746 512 0 495.254 0 474.668v-224c0-20.59 16.746-37.336 37.332-37.336h160c20.59 0 37.336 16.746 37.336 37.336v224c0 20.586-16.746 37.332-37.336 37.332zm-160-266.668A5.337 5.337 0 0 0 32 250.668v224A5.336 5.336 0 0 0 37.332 480h160a5.337 5.337 0 0 0 5.336-5.332v-224a5.338 5.338 0 0 0-5.336-5.336zM474.668 512h-160c-20.59 0-37.336-16.746-37.336-37.332v-96c0-20.59 16.746-37.336 37.336-37.336h160c20.586 0 37.332 16.746 37.332 37.336v96C512 495.254 495.254 512 474.668 512zm-160-138.668a5.338 5.338 0 0 0-5.336 5.336v96a5.337 5.337 0 0 0 5.336 5.332h160a5.336 5.336 0 0 0 5.332-5.332v-96a5.337 5.337 0 0 0-5.332-5.336zm160-74.664h-160c-20.59 0-37.336-16.746-37.336-37.336v-224C277.332 16.746 294.078 0 314.668 0h160C495.254 0 512 16.746 512 37.332v224c0 20.59-16.746 37.336-37.332 37.336zM314.668 32a5.337 5.337 0 0 0-5.336 5.332v224a5.338 5.338 0 0 0 5.336 5.336h160a5.337 5.337 0 0 0 5.332-5.336v-224A5.336 5.336 0 0 0 474.668 32zm0 0" />
              </svg>
              <span>Dashboard</span>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)" id="Art-btn" class="text-white text-sm flex flex-col items-center hover:bg-[#0C62C5] rounded px-4 py-5 transition-all">
              <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 mb-3" viewBox="0 0 24 24">
                <path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/>
                <path d="M14 17H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
              </svg>
              <span>Articles</span>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)" id="com-btn" class="text-white text-sm flex flex-col items-center hover:bg-[#0C62C5] rounded px-4 py-5 transition-all">
              <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 mb-3" viewBox="0 0 24 24">
                <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/>
                <path d="M7 9h10v2H7zm0-3h10v2H7z"/>
              </svg>
              <span>Comments</span>
            </a>
          </li>
          <div class="mt-2 text-center">
            <a href="logout.php" class="text-xm text-gray-300 mt-0.5 hover:text-white cursor-pointer">Logout</a>
          </div>
        </ul>
      </nav>


    <main class="p-8 w-full">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
    <div class="bg-white shadow-md rounded-lg p-6">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold">Total Articles</h2>
          <p class="text-gray-500"><?php echo $total_art; ?></p>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-8 h-8 text-blue-600" viewBox="0 0 16 16"><path d="M5 3h6v2H5zM3 7h10v2H3zM3 11h10v2H3zM5 15h6v2H5z"/></svg>
      </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold">Total Vues</h2>
          <p class="text-gray-500"><?php echo $total_vues; ?></p>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-8 h-8 text-green-600" viewBox="0 0 16 16"><path d="M5 3h6v2H5zM3 7h10v2H3zM3 11h10v2H3zM5 15h6v2H5z"/></svg>
      </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold">Total Comments</h2>
          <p class="text-gray-500"><?php echo $total_comt; ?></p>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-8 h-8 text-yellow-600" viewBox="0 0 16 16"><path d="M5 3h6v2H5zM3 7h10v2H3zM3 11h10v2H3zM5 15h6v2H5z"/></svg>
      </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold">Total Likes</h2>
          <p class="text-gray-500"><?php echo $total_likes; ?></p>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-8 h-8 text-red-600" viewBox="0 0 16 16"><path d="M5 3h6v2H5zM3 7h10v2H3zM3 11h10v2H3zM5 15h6v2H5z"/></svg>
        </div>
    </div>
  </div>

    <div class="mt-8">
        
    </div>

        <!--ssssssssssssssssssssssssssss-->
        
        <!---->
        <div id="AddFormModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white w-1/2 p-6 rounded shadow-md">
                    <div class="flex justify-end">
                    
                        <button id="closeAddForm" class="text-gray-700 hover:text-red-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <h2 class="text-2xl font-bold mb-4 text-[#056EE6]">Add Article</h2>
    
                <form id="ArtForm" method="POST">
                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Titre D'article</label>
                            <input type="text" id="titre" name="title"
                                   class="w-full p-2 border rounded-md focus:outline-none focus:border-blue-500">
                        </div>
                        <div class="mb-4">
                            <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Content</label>
                            <textarea id="description" name="content"
                                      class="w-full p-2 border rounded-md focus:outline-none focus:border-blue-500"></textarea>
                        </div>                       
                        <button type="submit" name="SubBtn"
                                class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                            Add
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!---->
        <div id="DeleteForm" class="fixed z-10 inset-0 overflow-y-auto hidden">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white w-1/2 p-6 rounded shadow-md">
                    <div class="flex justify-end">
                    
                        <button id="closeDelForm" class="text-gray-700 hover:text-red-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <h2 class="text-2xl font-bold mb-4 text-[#056EE6]">Delete Article</h2>
    
                    <form method="POST" action="">
                    <div class="mb-4">
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Select an article title to delete:</label>
                        <select name="titleToDelete" id="title" class="bg-transparent border-b-2 border-gray-300 py-2" required>
                            <option value="">-- Select Title --</option>
                            <?php
                            if($resultat->num_rows > 0) {
                                while($row = $resultat->fetch_assoc()) {
                                    echo "<option value='".$row['title']."'>".$row['title']."</option>";
                                }
                            }
                            ?>
                        </select>
                        <button type="submit" name="delete" class="bg-red-500 text-white font-bold py-2 px-4 ml-20 rounded hover:bg-red-700">Delete Article</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!---->
        <div class="text-gray-900 hidden" id="Article-content">
            <div class="p-4 flex justify-between">
                <h1 class="text-3xl font-bold">Blogs</h1>
                <div class="flex justify-end">
                    <button
                    type="button"
                    data-twe-ripple-init
                    data-twe-ripple-color="light"
                    id="openAddForm"
                    class="me-3 inline-block rounded bg-[#056EE6] px-6 pb-2 pt-2.5 text-xs font-bold uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-lg focus:bg-primary-accent-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-md dark:shadow-black/30 dark:hover:shadow-lg dark:focus:shadow-lg dark:active:shadow-lg">
                    Add Article
                    </button>
                    <button
                    type="button"
                    data-twe-ripple-init
                    data-twe-ripple-color="light"
                    id="editBtn"
                    class="me-3 inline-block rounded bg-green-700 px-6 pb-2 pt-2.5 text-xs font-bold uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-lg focus:bg-primary-accent-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-md dark:shadow-black/30 dark:hover:shadow-lg dark:focus:shadow-lg dark:active:shadow-lg">
                    Edit
                    </button>
                    <button
                    type="button"
                    data-twe-ripple-init
                    data-twe-ripple-color="light"
                    id="delBtn"
                    class="me-3 inline-block rounded bg-red-700 px-6 pb-2 pt-2.5 text-xs font-bold uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-lg focus:bg-primary-accent-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-md dark:shadow-black/30 dark:hover:shadow-lg dark:focus:shadow-lg dark:active:shadow-lg">
                    Delete
                    </button>
                </div>
            </div>
            <div class="px-3 py-4 flex justify-center">
                <table class="w-full text-md bg-white shadow-md rounded mb-4">
                    <tbody class="ArtContainer">
                        <tr class="border-b">
                            <th class="text-left p-3 px-28">Titre</th>
                            <th class="text-left p-3 px-28">Vues</th>
                            <th class="text-left p-3 px-28">Likes</th>
                            <th class="text-left p-3 px-28">Posted</th>
                        </tr>
                        
                        <?php
               
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr class='border-b hover:bg-blue-200 bg-gray-100'>";
                                echo "<td class='p-3 px-5'><input type='text' value='" . htmlspecialchars($row['title']) . "' disabled class='bg-transparent border-b-2 border-gray-300 py-2'></td>";
                                echo "<td class='p-3 px-5'><input type='text' value='" . htmlspecialchars($row['views']) . "' disabled class='bg-transparent border-b-2 border-gray-300 py-2'></td>";
                                echo "<td class='p-3 px-5'><input type='text' value='" . htmlspecialchars($row['likes']) . "' disabled class='bg-transparent border-b-2 border-gray-300 py-2'></td>";
                                echo "<td class='p-3 px-5'><input type='text' value='" . htmlspecialchars($row['create_dat']) . "' disabled class='bg-transparent border-b-2 border-gray-300 py-2'></td>";
                                echo "<input type='hidden' class='article-content' value='" . htmlspecialchars($row['content']) . "'>";
                                echo "</tr>";
                            }
                            ?>
                    </tbody>
                </table>
        
            </div>
        </div>
        <!---->
        
        <div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg shadow-lg p-6 w-1/2">
                <h2 class="text-lg font-bold mb-4">Edit Article</h2>
                <form method="POST">
                    <div class="mb-4">
                        <label for="titleToEdit" class="block text-gray-700 text-sm font-bold mb-2">Select an article to edit:</label>
                        <select name="originalTitle" id="titleToEdit" class="w-full bg-transparent border-b-2 border-gray-300 py-2" required onchange="loadArticleContent(this.value)">
                            <option value="">-- Select Title --</option>
                            <?php
                            $resultat->data_seek(0);
                            if($resultat->num_rows > 0) {
                                while($row = $resultat->fetch_assoc()) {
                                    echo "<option value='".$row['title']."'>".$row['title']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">New Title</label>
                        <input type="text" name="updatedTitle" id="updatedTitle" class="w-full px-4 py-2 border rounded" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Content</label>
                        <textarea name="updatedContent" id="updatedContent" class="w-full px-4 py-2 border rounded" rows="4"></textarea>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded" onclick="toggleEditModal(false)">Cancel</button>
                        <button type="submit" name="update" class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="Comments-content" class="hidden">
            <div class="text-gray-900">
                <div class="p-4 flex justify-between">
                    <h1 class="text-3xl font-bold">Comments Management</h1>
                </div>
                <div class="px-3 py-4 flex justify-center">
                    <table class="w-full text-md bg-white shadow-md rounded mb-4">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left p-3 px-5">Visitor</th>
                                <th class="text-left p-3 px-5">Email</th>
                                <th class="text-left p-3 px-5">Article</th>
                                <th class="text-left p-3 px-5">Comment</th>
                                <th class="text-left p-3 px-5">Date</th>
                                <th class="text-left p-3 px-5">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $comments_query = "SELECT c.*, v.name_visit, v.email, a.title as article_title 
                                           FROM Comment c 
                                           JOIN Visitor v ON c.Visit_id = v.Visit_id 
                                           JOIN Article a ON c.id_art = a.id_art 
                                           WHERE a.Author_id = " . $_SESSION['Author_id'] . "
                                           ORDER BY c.create_dat DESC";
                            $comments_result = $conn->query($comments_query);
                            
                            while ($comment = $comments_result->fetch_assoc()):
                            ?>
                            <tr class="border-b hover:bg-orange-100">
                                <td class="p-3 px-5"><?php echo htmlspecialchars($comment['name_visit']); ?></td>
                                <td class="p-3 px-5"><?php echo htmlspecialchars($comment['email']); ?></td>
                                <td class="p-3 px-5"><?php echo htmlspecialchars($comment['article_title']); ?></td>
                                <td class="p-3 px-5"><?php echo htmlspecialchars(substr($comment['content'], 0, 50)) . '...'; ?></td>
                                <td class="p-3 px-5"><?php echo date('Y-m-d H:i', strtotime($comment['create_dat'])); ?></td>
                                <td class="p-3 px-5">
                                    <form method="POST" action="" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                        <input type="hidden" name="comment_id" value="<?php echo $comment['cmt_id']; ?>">
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/tw-elements/js/tw-elements.umd.min.js"></script>
    <script defer>

        const openAddFormButton = document.getElementById('openAddForm');
        const closeAddFormButton = document.getElementById('closeAddForm');
        const AddFormModal = document.getElementById('AddFormModal');
        const DelBtn = document.getElementById('delBtn');
        const closeDelButton = document.getElementById('closeDelForm');
        const deleteForm = document.getElementById('DeleteForm');
        const editBtn = document.getElementById('editBtn');
        const dashBtn = document.getElementById('dash-btn');
        const ArticleContent = document.getElementById('Article-content');
        const ArtBtn = document.getElementById('Art-btn');
        const comBtn = document.getElementById('com-btn');
        const CommentsContent = document.getElementById('Comments-content');
        
        openAddFormButton.addEventListener('click', () => {
            AddFormModal.classList.remove('hidden');
        });

        closeAddFormButton.addEventListener('click', () => {
            AddFormModal.classList.add('hidden');
        });

        delBtn.addEventListener('click', () => {
            deleteForm.classList.remove('hidden');
        });

        closeDelButton.addEventListener('click', () => {
            deleteForm.classList.add('hidden');
        });

        dashBtn.addEventListener('click',()=>{
            dashBtn.style.backgroundColor = '#0C62C5';
            ArtBtn.style.backgroundColor = '';
            comBtn.style.backgroundColor = '';
            ArticleContent.classList.add('hidden');
            CommentsContent.classList.add('hidden');
        });
        ArtBtn.addEventListener('click',()=>{
            ArtBtn.style.backgroundColor = '#0C62C5';
            dashBtn.style.backgroundColor = '';
            comBtn.style.backgroundColor = '';
            ArticleContent.classList.remove('hidden');
            CommentsContent.classList.add('hidden');
        });
        comBtn.addEventListener('click',()=>{
            comBtn.style.backgroundColor = '#0C62C5';
            dashBtn.style.backgroundColor = '';
            ArtBtn.style.backgroundColor = '';
            ArticleContent.classList.add('hidden');
            CommentsContent.classList.remove('hidden');
        });


        function toggleEditModal(show) {
        const modal = document.getElementById('editModal');
        modal.style.display = show ? 'flex' : 'none';
        
        if (!show) {
            document.getElementById('titleToEdit').value = '';
            document.getElementById('updatedTitle').value = '';
            document.getElementById('updatedContent').value = '';
        }
        }

        function loadArticleContent(title) {
            if (!title) return;
            
            const articleRows = document.querySelectorAll('.ArtContainer tr');
            articleRows.forEach(row => {
                const titleInput = row.querySelector('input[type="text"]');
                if (titleInput && titleInput.value === title) {
                    document.getElementById('updatedTitle').value = title;
                    const contentInput = row.querySelector('.article-content');
                    document.getElementById('updatedContent').value = contentInput.value;
                }
            });
        }

        editBtn.addEventListener('click', () => {
            toggleEditModal(true);
        });

        </script>


</body>
</html>