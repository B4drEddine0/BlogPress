<?php 
session_start();
include ("connexion.php");
$query = "SELECT * FROM article";
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
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        }else {
        
        }

        if (isset($_POST['delete'])) {
            $titleToDelete = $_POST['titleToDelete'] ?? '';
    
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
    
        $resultat = $conn->query("SELECT title FROM article");
        
        $total_art_result = $conn->query('SELECT COUNT(id_art) FROM article');
        $total_art = $total_art_result->fetch_assoc();
        $total_art = $total_art['COUNT(id_art)'];

        $total_vues_result = $conn->query('SELECT SUM(views) as total_views FROM article WHERE Author_id = ' . $_SESSION['Author_id']);
        $total_vues = $total_vues_result->fetch_assoc();
        $total_vues = $total_vues['total_views'];

        $total_comt_result = $conn->query('SELECT COUNT(id_art) FROM article');
        $total_comt = $total_comt_result->fetch_assoc();
        $total_comt = $total_comt['COUNT(id_art)'];

        $total_likes_result = $conn->query('SELECT SUM(likes) as total_likes FROM article WHERE Author_id = ' . $_SESSION['Author_id']);
        $total_likes = $total_likes_result->fetch_assoc();
        $total_likes = $total_likes['total_likes'];

?>


<?php
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
?>
<?php
if(isset($_GET['getArticle'])) {
    $title = $_GET['title'];
    $stmt = $conn->prepare("SELECT content FROM article WHERE title = ?");
    $stmt->bind_param("s", $title);
    $stmt->execute();
    $result = $stmt->get_result();
    if($row = $result->fetch_assoc()) {
        echo json_encode(['content' => $row['content']]);
    }
    exit;
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
        <div class="flex flex-col items-center cursor-pointer px-4" onclick="window.location.href = 'index.html'">
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
              <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 mb-3" viewBox="0 0 511.414 511.414">
                <path d="M497.695 108.838a16.002 16.002 0 0 0-9.92-14.8L261.787 1.2a16.003 16.003 0 0 0-12.16 0L23.639 94.038a16 16 0 0 0-9.92 14.8v293.738a16 16 0 0 0 9.92 14.8l225.988 92.838a15.947 15.947 0 0 0 12.14-.001c.193-.064-8.363 3.445 226.008-92.837a16 16 0 0 0 9.92-14.8zm-241.988 76.886-83.268-34.207L352.39 73.016l88.837 36.495zm-209.988-51.67 71.841 29.513v83.264c0 8.836 7.164 16 16 16s16-7.164 16-16v-70.118l90.147 37.033v257.797L45.719 391.851zM255.707 33.297l55.466 22.786-179.951 78.501-61.035-25.074zm16 180.449 193.988-79.692v257.797l-193.988 79.692z" />
              </svg>
              <span>Articles</span>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)" class="text-white text-sm flex flex-col items-center hover:bg-[#0C62C5] rounded px-4 py-5 transition-all">
              <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 mb-3" viewBox="0 0 16 16">
                <path d="M13 .5H3A2.503 2.503 0 0 0 .5 3v10A2.503 2.503 0 0 0 3 15.5h10a2.503 2.503 0 0 0 2.5-2.5V3A2.503 2.503 0 0 0 13 .5ZM14.5 13a1.502 1.502 0 0 1-1.5 1.5H3A1.502 1.502 0 0 1 1.5 13v-.793l3.5-3.5 1.647 1.647a.5.5 0 0 0 .706 0L10.5 7.207V8a.5.5 0 0 0 1 0V6a.502.502 0 0 0-.5-.5H9a.5.5 0 0 0 0 1h.793L7 9.293 5.354 7.647a.5.5 0 0 0-.707 0l-3.5 3.5v.793a1.502 1.502 0 0 1 1.5-1.5h10a1.502 1.502 0 0 1 1.5 1.5z" />
              </svg>
              <span>Comments</span>
            </a>
          </li>
          <div class="mt-2 text-center">
            <p class="text-xm text-gray-300 mt-0.5">Logout</p>
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
          <p class="text-gray-500">25</p>
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
                    <h2 class="text-2xl font-bold mb-4 text-[#056EE6]">Add Article</h2>
    
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
                    <tbody class="questionsContainer">
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

        <!-- <div id="Comment_gest" class="fixed z-10 inset-0 overflow-y-auto hidden">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white w-1/2 p-6 rounded shadow-md">
                    <div class="flex justify-end">
                    
                        <button id="closeGestForm" class="text-gray-700 hover:text-red-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <h2 class="text-2xl font-bold mb-4 text-[#056EE6]">Add Question</h2>
    
                    <form id="CommentsForm" method="post">
                        <div class="mb-4">
                            <label for="QuizTitre" class="block text-gray-700 text-sm font-bold mb-2">Quiz associé</label>
                            <select id="QuizTitre" name="QuizTitre" class="w-full p-2 border rounded-md focus:outline-none focus:border-blue-500">
                                
                            </select>
                        </div>

                    
                        <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                            Add
                        </button>
                    </form>
                    
                </div>
            </div>
        </div> -->


        

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
            dashBtn.style.backgroundColor= '#0C62C5';
            ArtBtn.style.backgroundColor='';
            ArticleContent.classList.add('hidden');

        });
        ArtBtn.addEventListener('click',()=>{
            ArtBtn.style.backgroundColor='#0C62C5';
            dashBtn.style.backgroundColor='';
            ArticleContent.classList.remove('hidden');
        })

        </script>


<script>
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
    
    document.getElementById('updatedTitle').value = title;
    
    fetch(`admin.php?getArticle=1&title=${encodeURIComponent(title)}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('updatedContent').value = data.content;
        })
        .catch(error => console.error('Error:', error));
}

const editButton = document.getElementById('editBtn');
editButton.addEventListener('click', () => {
    toggleEditModal(true);
});
</script>

</body>
</html>