<?php
// Check existence of VendorID parameter before processing further
if(isset($_GET["VendorID"]) && !empty(trim($_GET["VendorID"]))){
    // Include config file
    require_once "config.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM Vendor WHERE VendorID = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["VendorID"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $VendorID = $row["VendorID"];
                $VName = $row["VName"];
                $City = $row["City"];
            } else{
                // URL doesn't contain valid VendorID parameter. Redirect to error page
                header("location: Vendor_error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // URL doesn't contain VendorID parameter. Redirect to error page
    header("location: Vendor_error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>View Record</h1>
                    </div>
                    <div class="form-group">
                        <label>Vendor ID</label>
                        <p class="form-control-static"><?php echo $row["VendorID"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Vendor Name</label>
                        <p class="form-control-static"><?php echo $row["VName"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <p class="form-control-static"><?php echo $row["City"]; ?></p>
                    </div>                    
                    <p><a href="Vendor_mgmt.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
