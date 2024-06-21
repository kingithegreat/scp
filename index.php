<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SCP Application</title>
      

     <!--BOOTSTRAP-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="styles/styles.css">
    
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
      
      include "connection.php";?>
      <!--HEADER-->
     
      <div class="container-fluid bg-black text-danger text-center rounded ">

         <h1 id="heading" class="mb-1 pt-3 text-danger text-center display-1 fw-bold " >SCP FOUNDATION</h1>
      </div>
        <!--Navigation-->
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded  pt-0">
        <div class="container-fluid">
            <img class="m-3" src="images/footerImage.jpg" alt="" style="height: 40px;">
          
           <!-- <a class="navbar-brand text-danger fs-2 fw-bold fst-italic ms-3" href="#">
                <i class="fas fa-shield-alt"></i   >SCP
            </a>-->
    
         
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
    
          
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item active">
                    <a href="create.php" class="nav-link text-light  ">Add New Record</a>
                  </li>
                  <?php foreach($Result as $link): ?>
                    <li class="nav-item active"><a href="index.php?link='<?php echo $link['Item']; ?>'" class="nav-link text-danger"><?php echo $link['Item']; ?></a></li>
                  <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </nav>
    <br><br>
      <div id="displayContainer" class="rounded border shadow p-5 container ">
          
          <?php 
            
            if(isset($_GET['link']))
            {
                // trim out the single quote from get value
                $Item = trim($_GET['link'], "'");
         
                
                // prepared statement
                $statement = $connection->prepare("select * from scp where Item = ?");
                if(!$statement)
                {
                    echo "<p>Error in preparing sql statement</p>";
                    exit;
                }
                // bind parameters takes 2 arguments the type of data and the var to bind to.
                $statement->bind_param("s", $Item);
                
                if($statement->execute())
                {
                    $get_result = $statement->get_result();
                    
                    // check if record has been retrieved
                    if($get_result->num_rows > 0)
                    {
                        $array = array_map('htmlspecialchars', $get_result->fetch_assoc());
                         
                        $update = "update.php?update=" . $array['ID'];
                        $delete = "index.php?delete=" .$array['ID'];
                         
                         echo "<h2 class='display-2 '>{$array['Item']}</h2>
                               <h3 class='display-3'>{$array['Class']}</h3>";
                        
                        if(!empty($array['Image']))
                        {
                            echo "
                            <p class='text-center '><img src='{$array['Image']}' alt='{$array['Item']}' class=' shadow rounded img-fluid'></p>
                            ";
                        }
                        
                        echo "<p>{$array['Containment']}</p>
                              <p>{$array['Description']}</p>
                              <p class='buttons'><a href='{$update}' class='btn btn-dark'>Update Record</a> &nbsp;
                              <a href='{$delete}' class='btn btn-danger'>Delete Record</a></p>";
                        
                    }
                    else
                    {
                        echo "<p>No record found for Item: {$array['Item']}</p>";
                    }
                }
                else
                {
                    echo "<p>Error executing statement.</p>";
                }
                
              
            }
            else
            {
                echo "
         
                    <br><br>
                        <p id='indexText' >Welcome to the SCP database. Use the links above to view current records in the database or create a new entry.</p>
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
                        </div>

                        
                        
                ";
            }
          
          // Delete function
            if(isset($_GET['delete']))
            {
               $deleteID = $_GET['delete'];
               $delete_query = $connection->prepare("delete from scp where ID = ?");
               $delete_query->bind_param("i", $deleteID);
               
               if($delete_query->execute())
               {
                   echo "<div class='alert alert-danger'>Recorded Deleted...</div>";
               }
               else
               {
                    echo "<div class='alert alert-danger'>Error: {$delete_query->error}</div>";
               }
            } // end of delete funtionality
            
          ?>
          
      </div>
      <footer class="py-4">
        <div class="container">
            <div class="row align-items-center py-4">
                <div class="col-12 col-lg-3 text-center text-lg-start mb-3 mb-lg-0"><img alt="footerLogo" class="img-fluid rounded" height="" src="images/footerImage.jpg" width="300" height="100"></div>
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
                    <a class="text-muted" href="">English <svg class="bi bi-chevron-down ms-1" fill="currentColor" height="16" viewbox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" fill-rule="evenodd"></path></svg></a>
                </div>
            </div>
            <div class="border-top d-lg-none"></div>
            <div class="text-center py-3">
                <a class="me-2" href=""><svg class="bi bi-pinterest text-primary" fill="currentColor" height="20" viewbox="0 0 16 16" width="20" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 0a8 8 0 0 0-2.915 15.452c-.07-.633-.134-1.606.027-2.297.146-.625.938-3.977.938-3.977s-.239-.479-.239-1.187c0-1.113.645-1.943 1.448-1.943.682 0 1.012.512 1.012 1.127 0 .686-.437 1.712-.663 2.663-.188.796.4 1.446 1.185 1.446 1.422 0 2.515-1.5 2.515-3.664 0-1.915-1.377-3.254-3.342-3.254-2.276 0-3.612 1.707-3.612 3.471 0 .688.265 1.425.595 1.826a.24.24 0 0 1 .056.23c-.061.252-.196.796-.222.907-.035.146-.116.177-.268.107-1-.465-1.624-1.926-1.624-3.1 0-2.523 1.834-4.84 5.286-4.84 2.775 0 4.932 1.977 4.932 4.62 0 2.757-1.739 4.976-4.151 4.976-.811 0-1.573-.421-1.834-.919l-.498 1.902c-.181.695-.669 1.566-.995 2.097A8 8 0 1 0 8 0z"></path></svg></a> <a class="me-2" href=""><svg class="bi bi-twitter text-primary" fill="currentColor" height="20" viewbox="0 0 16 16" width="20" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"></path></svg></a> <a class="me-2" href=""><svg class="bi bi-facebook text-primary" fill="currentColor" height="20" viewbox="0 0 16 16" width="20" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"></path></svg></a>
            </div>
            <div class="pt-lg-4 pb-4 pb-lg-3 text-center small ">
                <p class="mb-0"><a class="d-block d-lg-inline text-light mx-lg-1 mb-2 mb-lg-0" href="https://scp-wiki.wikidot.com/site-rules">Privacy Policy</a> <a class="d-block d-lg-inline text-light mx-lg-1 mb-2 mb-lg-0" href="https://scp-wiki.wikidot.com/licensing-guide">Terms of Service</a> <a class="d-block d-lg-inline text-light mx-lg-1 mb-2 mb-lg-0" href="https://scp-wiki.wikidot.com/about-the-scp-foundation">Site Map</a></p>
                <p class="text-light mb-0">© 2024 SCP Foundation All rights reserved.</p>
            </div>
        </div>
    </footer>

  </body>
</html>
