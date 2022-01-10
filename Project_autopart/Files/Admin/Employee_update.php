<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$EFName = $ELName = $DID = "";
$EFName_err = $ELName_err = $DID_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["EID"]) && !empty($_POST["EID"])){
    // Get hidden input value
    $EID = $_POST["EID"];
    
    // Validate EFname
    $input_EFName = trim($_POST["EFName"]);
    if(empty($input_EFName)){
        $EFName_err = "Please enter a First Name.";
    } elseif(!filter_var($input_EFName, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $EFName_err = "Please enter a valid First Name.";
    } else{
        $EFName = $input_EFName;
    }
    
    // Validate ELName
    $input_ELName = trim($_POST["ELName"]);
    if(empty($input_ELName)){
        $ELName_err = "Please enter an Last Name.";     
    } elseif(!filter_var($input_ELName, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $ELName_err = "Please enter a valid Last Name.";
	} else{
        $ELName = $input_ELName;
    }
	// Validate DID
    $input_DID = trim($_POST["DID"]);
    if(empty($input_DID)){
        $DID_err = "Please enter an Department ID.";     
    } else{
        $DID = $input_DID;
    }
    
    
    // Check input errors before inserting in database
    if(empty($EFName_err) && empty($ELName_err) && empty($DID_err)){
        // Prepare an update statement
        $sql = "UPDATE employee SET EFName=?, ELName=?, DID=? WHERE EID=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_EFName, $param_ELName, $param_DID, $param_EID);
            
            // Set parameters
            $param_EFName = $EFName;
            $param_ELName = $ELName;
            $param_DID = $DID;
            $param_EID = $EID;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: employee_mgmt.php");
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
    if(isset($_GET["EID"]) && !empty(trim($_GET["EID"]))){
        // Get URL parameter
        $EID =  trim($_GET["EID"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM employee WHERE EID = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_EID);
            
            // Set parameters
            $param_EID = $EID;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $EFName = $row["EFName"];
                    $ELName = $row["ELName"];
					$DID = $row["DID"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: employee_error.php");
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
        header("location: employee_error.php");
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
                        <div class="form-group <?php echo (!empty($EFName_err)) ? 'has-error' : ''; ?>">
                            <label>First Name</label>
                            <input type="text" name="EFName" class="form-control" value="<?php echo $EFName; ?>">
                            <span class="help-block"><?php echo $EFName_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($ELName_err)) ? 'has-error' : ''; ?>">
                            <label>Last Name</label>
                            <input type="text" name="ELName" class="form-control" value="<?php echo $ELName; ?>">
                            <span class="help-block"><?php echo $ELName_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($DID_err)) ? 'has-error' : ''; ?>">
                            <label>Department ID</label>
                            <input type="text" name="DID" class="form-control" value="<?php echo $DID; ?>">
                            <span class="help-block"><?php echo $DID_err;?></span>
                        </div>
						<input type="hidden" name="EID" value="<?php echo $EID; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="employee_mgmt.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>