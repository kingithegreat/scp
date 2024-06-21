<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SCP Application</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles/styles.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
  </head>
  <body class="container-fluid">
      <?php
      // Enable error reporting
      error_reporting(E_ALL);
      // Display errors
      ini_set('display_errors', 1);
      
      // Include the database connection file
      include "connection.php";
      ?>

      <!-- Header -->
      <div class="container-fluid bg-black text-danger text-center rounded">
        <h1 id="heading" class="mb-1 pt-3 text-danger text-center display-1 fw-bold">SCP FOUNDATION</h1>
      </div>

      <!-- Navigation -->
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded pt-0">
        <div class="container-fluid">
          <img class="m-3" src="images/footerImage.jpg" alt="" style="height: 40px;">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item active">
                <a href="create.php" class="nav-link text-light">Add New Record</a>
              </li>
              <?php foreach($Result as $link): ?>
                <li class="nav-item active">
                  <a href="index.php?link='<?php echo $link['Item']; ?>'" class="nav-link text-danger"><?php echo $link['Item']; ?></a>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </nav>
      <br><br>

      <!-- Display Container -->
      <div id="displayContainer" class="rounded border shadow p-5 container">
        <?php
        if (isset($_GET['link'])) {
          // Trim out the single quote from get value
          $Item = trim($_GET['link'], "'");
          // Prepared statement
          $statement = $connection->prepare("SELECT * FROM scp WHERE Item = ?");
          if (!$statement) {
            echo "<p>Error in preparing SQL statement</p>";
            exit;
          }
          // Bind parameters
          $statement->bind_param("s", $Item);
          if ($statement->execute()) {
            $get_result = $statement->get_result();
            // Check if record has been retrieved
            if ($get_result->num_rows > 0) {
              $array = array_map('htmlspecialchars', $get_result->fetch_assoc());
              $update = "update.php?update=" . $array['ID'];
              $delete = "index.php?delete=" . $array['ID'];
              echo "<h2 class='display-2'>{$array['Item']}</h2>
                    <h3 class='display-3'>{$array['Class']}</h3>";
              if (!empty($array['Image'])) {
                echo "<p class='text-center'><img src='{$array['Image']}' alt='{$array['Item']}' class='shadow rounded img-fluid'></p>";
              }
              echo "<p>{$array['Containment']}</p>
                    <p>{$array['Description']}</p>
                    <p class='buttons'><a href='{$update}' class='btn btn-dark'>Update Record</a> &nbsp;
                    <a href='{$delete}' class='btn btn-danger'>Delete Record</a></p>";
            } else {
              echo "<p>No record found for Item: {$array['Item']}</p>";
            }
          } else {
            echo "<p>Error executing statement.</p>";
          }
        } else {
          // Default welcome message
          echo "<br><br>
                <p id='indexText'>Welcome to the SCP database. Use the links above to view current records in the database or create a new entry.</p>
                <br><br>
                <div id='carouselExample' class='carousel slide'>
                  <div class='carousel-inner'>
                    <div class='carousel-item active'>
                      <img src='images/default1.jpg' class='d-block w-70 h-70 mx-auto' alt='scp image'>
                    </div>
                    <div class='carousel-item'>
                      <img src='images/default2.jpg' class='d-block w-70 h-70 mx-auto' alt='scp image'>
                    </div>
                    <div class='carousel-item'>
                      <img src='images/default4.jpg' class='d-block w-70 h-70 mx-auto' alt='scp image'>
                    </div>
                  </div>
                  <button class='carousel-control-prev' type='button' data-bs-target='#carouselExample' data-bs-slide='prev'>
                    <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                    <span class='visually-hidden'>Previous</span>
                  </button>
                  <button class='carousel-control-next' type='button' data-bs-target='#carouselExample' data-bs-slide='next'>
                    <span class='carousel-control-next-icon' aria-hidden='true'></span>
                    <span class='visually-hidden'>Next</span>
                  </button>
                </div>";
        }

        // Delete functionality
        if (isset($_GET['delete'])) {
          $deleteID = $_GET['delete'];
          $delete_query = $connection->prepare("DELETE FROM scp WHERE ID = ?");
          $delete_query->bind_param("i", $deleteID);
          if ($delete_query->execute()) {
            echo "<div class='alert alert-danger'>Record Deleted...</div>";
          } else {
            echo "<div class='alert alert-danger'>Error: {$delete_query->error}</div>";
          }
        }
        ?>
      </div>

      <!-- Footer -->
      <footer class="py-4">
        <div class="container">
          <div class="row align-items-center py-4">
            <div class="col-12 col-lg-3 text-center text-lg-start mb-3 mb-lg-0">
              <img alt="footerLogo" class="img-fluid rounded" src="images/footerImage.jpg" width="300" height="100">
            </div>
            <div class="col-12 col-lg-6 navbar-expand text-center mb-3 mb-lg-0">
              <ul class="list-unstyled d-block d-lg-flex justify-content-center">
                <li class="nav-item">
                  <a class="text-light text-decoration-none me-lg-3" href="">Home</a>
                </li>
                <li class="nav-item">
                  <a class="text-light text-decoration-none me-lg-3" href="https://scp-wiki.wikidot.com/about-the-scp-foundation">About</a>
                </li>
                <li class="nav-item">
                  <a class="text-light text-decoration-none me-lg-3" href="https://05command.wikidot.com/staff-list">Staff</a>
                </li>
                <li class="nav-item">
                  <a class="text-light text-decoration-none me-lg-3" href="https://scp-wiki.wikidot.com/guide-hub">Guide</a>
                </li>
                <li class="nav-item">
                  <a class="text-light text-decoration-none" href="https://scp-wiki.wikidot.com/contact-staff">Contact</a>
                </li>
              </ul>
            </div>
            <div class="col-12 col-lg-3 small text-center text-lg-end">
              <a class="text-light" href="">English <svg class="bi bi-chevron-down ms-1" fill="currentColor" height="16" viewBox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"></path>
              </svg></a>
              <p class="text-light mb-0">© 2024 SCP FOUNDATION</p>
            </div>
          </div>
        </div>
      </footer>
  </body>
</html>
