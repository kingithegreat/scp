<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/styles.css"> <!-- Link to external stylesheet -->
  </head>
  <body class="container">
      
    <?php
      // Enable error reporting
      error_reporting(E_ALL);

      // Display errors
      ini_set('display_errors', 1);

      // Include the database connection file
      include "connection.php";
        
      // Initialise $row as an empty array
      $row = [];
        
      // Check if 'update' parameter is set in the URL
      if(isset($_GET['update'])) {
        $id = $_GET['update'];
        // Prepare SQL statement to select the record based on ID
        $recordID = $connection->prepare("select * from scp where ID = ?");
        
        if(!$recordID) {
          echo "<div class='alert alert-danger p-3 m-2'>Error preparing record for updating.</div>";
          exit;
        }
        
        // Bind the ID parameter
        $recordID->bind_param("i", $id);
        
        // Execute the query
        if($recordID->execute()) {
          echo "<div class='alert alert-success p-3 m-2'>Record ready for updating.</div>";
          $temp = $recordID->get_result();
          $row = $temp->fetch_assoc(); // Fetch the record as an associative array
        } else {
          echo "<div class='alert alert-danger p-3 m-2'>Error: {$recordID->error}</div>";
        }
      }
        
      // Check if the form is submitted for updating the record
      if(isset($_POST['update'])) {
        // Prepare SQL statement to update the record
        $update = $connection->prepare("update scp set Item=?, Class=?, Description=?, Containment=?, Image=? where ID=?");
        
        // Bind the form data to the SQL statement
        $update->bind_param("sssssi", $_POST['Item'], $_POST['Class'], $_POST['Description'], $_POST['Containment'], $_POST['Image'], $_POST['ID']);
        
        // Execute the update query
        if($update->execute()) {
          echo "<div class='alert alert-success p-3 m-2'>Record updated successfully</div>";
        } else {
          echo "<div class='alert alert-danger p-3 m-2'>Error: {$update->error}</div>";
        }
      }
    ?>
      
    <div class="container border-solid">
      <h1 class="text-white">Update record</h1>
        
      <p><a href="index.php" class="btn btn-danger">Back to index page.</a></p>
        
      <!-- Update form -->
      <form method="post" action="update.php" class="form-group container">
        <!-- Hidden input to store the record ID -->
        <input type="hidden" name="ID" value="<?php echo isset($row['ID']) ? $row['ID'] : ''; ?>">
        
        <label class="text-white">Scp Item:</label>
        <br>
        <input type="text" name="Item" placeholder="Item..." class="form-control" value="<?php echo isset($row['Item']) ? $row['Item'] : ''; ?>">
        <br><br>
        
        <label class="text-white">Class:</label>
        <br>
        <input type="text" name="Class" placeholder="Class..." class="form-control" value="<?php echo isset($row['Class']) ? $row['Class'] : ''; ?>">
        <br><br>
        
        <label class="text-white">SCP Description:</label>
        <br>
        <textarea name="Description" class="form-control"><?php echo isset($row['Description']) ? $row['Description'] : ''; ?></textarea>
        <br><br>
        
        <label class="text-white">SCP Containment:</label>
        <br>
        <textarea name="Containment" class="form-control"><?php echo isset($row['Containment']) ? $row['Containment'] : ''; ?></textarea>
        <br><br>
        
        <label class="text-white">Image:</label>
        <br>
        <input type="text" name="Image" placeholder="images/name_of_image.png" class="form-control" value="<?php echo isset($row['Image']) ? $row['Image'] : ''; ?>">
        <br><br>
        
        <!-- Submit button to update the record -->
        <input type="submit" name="update" class="btn btn-primary">
      </form>
    </div>
    
    <!-- Bootstrap JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
