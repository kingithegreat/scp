<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Record</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet"> 

     <!--custom CSS-->
     <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
     
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    </head>
    
    <body class="container">


    <!--HEADER-->
  
     <br><br>
      <div class="container-fluid bg-black text-danger text-center rounded">
         <h1 id="heading" class="mb-4 text-danger text-center display-1 fw-bold" >SCP FOUNDATION</h1>
      </div>
    
    <div id="displayContainer"class="rounded border shadow p-5"> 
    
    <?php
            include "connection.php";
            
            if(isset($_POST['submit']))
            {
               
               // write a prepare statement to insert data
                $insert = $connection->prepare("insert into scp(Item, Class, Containment, Description, Image) values(?,?,?,?,?)");
                 
                 
                //Bind parameters 
                $insert->bind_param("sssss", $_POST['Item'], $_POST['Class'], $_POST['Containment'], $_POST['Description'], $_POST['Image']);
                
                //execute SQL command
                if($insert->execute())
                {
                    echo "<div class='alert alert-success mb-3'>Record added successfully</div>";
                }
                else
                {
                    echo "<div class='alert alert-danger'>Error adding record...</div>";
                    //{$insert->error}
                }
            
            }
            
            
      ?>
      
      
    <h1>Create a new record</h1>
    <p> <a href = "index.php" class="btn btn-danger">Back to index page</a></p>
    
    <form method="post" action="create.php" class="form-group">
        <label>Enter SCP Item Number:</label>
        <br>
        <input type="text" name="Item" placeholder="Item No..." class="form-control" required>
        <br><br>
        
        <label>Enter Class:</label>
        <br>
        <input type="text" name="Class" placeholder="Class..." class="form-control" required>
        <br><br>
        
        <label>Enter Containment Protocol:</label>
        <br>
        <textarea name="Containment" class="form-control" required> Containtment Protocol...</textarea>
        <br><br>
        
        <label>Enter Description:</label>
        <br>
        <textarea name="Description" class="form-control" required> Description...</textarea>
        <br><br>
        
        <label>Enter Image:</label>
        <br>
        <input type="text" id="listBoxInput" name="Image" placeholder="Select image from drop down menu" class="form-control">
        <br><br>
        <div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Select Image</button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" name="Image" href="#">images/default1.jpg</a></li>
        <li><a class="dropdown-item" name="Image" href="#">images/default2.jpg</a></li>
        <li><a class="dropdown-item" name="Image" href="#">images/default3.jpg</a></li>
        <li><a class="dropdown-item" name="Image" href="#">images/default4.jpg</a></li>
        <li><a class="dropdown-item" name="Image" href="#">images/default5.jpg</a></li>
    </ul>
    </div>
    
     <br><br>
        <input type="submit" name="submit" class="btn btn-primary">
        
    </form>
    
    <script>
    // Get all dropdown items
    var dropdownItems = document.querySelectorAll('.dropdown-item');

    // Add click event listener to each dropdown item
    dropdownItems.forEach(function(item) {
        item.addEventListener('click', function(e) {
            // Prevent the default action
            e.preventDefault();

            // Get the text of the clicked dropdown item
            var text = e.target.textContent;

            // Set the text as the value of the list box input
            document.getElementById('listBoxInput').value = text;
        });
    });
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
