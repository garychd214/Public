<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$VendorID = $VName = $City = "";
$VendorID_err = $VName_err = $City_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["VendorID"]) && !empty($_POST["VendorID"])){
    // Get hidden input value
    $VendorID = $_POST["VendorID"];
    
    // Validate VName
    $input_VName = trim($_POST["VName"]);
    if(empty($input_VName)){
        $VName_err = "Please enter an Last Name.";     
    } else{
        $VName = $input_VName;
    }
	// Validate City
    $input_City = trim($_POST["City"]);
    if(empty($input_City)){
        $City_err = "Please enter an Department ID.";     
    } else{
        $City = $input_City;
    }
    
    
    // Check input errors before inserting in database
    if(empty($VendorID_err) && empty($VName_err) && empty($City_err)){
        // Prepare an update statement
        $sql = "UPDATE Vendor SET VName=?, City=? WHERE VendorID=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_VName, $param_City, $param_VendorID);
            
            // Set parameters
            $param_VName = $VName;
            $param_City = $City;
            $param_VendorID = $VendorID;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: Vendor_mgmt.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["VendorID"]) && !empty(trim($_GET["VendorID"]))){
        // Get URL parameter
        $VendorID =  trim($_GET["VendorID"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM Vendor WHERE VendorID = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_VendorID);
            
            // Set parameters
            $param_VendorID = $VendorID;
            
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
                    // URL doesn't contain valid id. Redirect to error page
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
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: Vendor_error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($VName_err)) ? 'has-error' : ''; ?>">
                            <label>Vendor Name</label>
                            <input type="text" name="VName" class="form-control" value="<?php echo $VName; ?>">
                            <span class="help-block"><?php echo $VName_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($City_err)) ? 'has-error' : ''; ?>">
                            <label>City</label>
                            <input type="text" name="City" class="form-control" value="<?php echo $City; ?>">
                            <span class="help-block"><?php echo $City_err;?></span>
                        </div>
						<input type="hidden" name="VendorID" value="<?php echo $VendorID; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="Vendor_mgmt.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>