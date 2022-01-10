<?php
// Check existence of PartNo parameter before processing further
if(isset($_GET["PartNo"]) && !empty(trim($_GET["PartNo"]))){
    // Include config file
    require_once "config.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM parts_info WHERE PartNo = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["PartNo"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $PartName = $row["PartName"];
                $VendorID = $row["VendorID"];
                $VehicleID = $row["VehicleID"];
				$Price = $row["Price"];
            } else{
                // URL doesn't contain valid PartNo parameter. Redirect to error page
                header("location: Part_error.php");
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
    // URL doesn't contain PartNo parameter. Redirect to error page
    header("location: Part_error.php");
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
                        <label>Part Name</label>
                        <p class="form-control-static"><?php echo $row["PartName"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Vendor ID</label>
                        <p class="form-control-static"><?php echo $row["VendorID"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>VehicleID</label>
                        <p class="form-control-static"><?php echo $row["VehicleID"]; ?></p>
                    </div>                    
					<div class="form-group">
                        <label>Price</label>
                        <p class="form-control-static"><?php echo $row["Price"]; ?></p>
                    </div>
                    <p><a href="Part_mgmt.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
