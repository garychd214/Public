<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values

$EFName = $ELName = $DID = "";
$EFName_err = $ELName_err = $DID_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate EFName
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
        $DID_err = "Please enter an Department Id.";     
    } else{
        $DID = $input_DID;
    }
    
    
    // Check input errors before inserting in database
    if(empty($EFName_err) && empty($ELName_err) && empty($DID_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO employee (EFName, ELName, DID) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_EFName, $param_ELName, $param_DID);
            
            // Set parameters
            $param_EFName = $EFName;
            $param_ELName = $ELName;
            $param_DID = $DID;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: Employee_mgmt.php");
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
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                        <h2>Adding New employee</h2>
                    </div>
                    <p>Please fill this form and submit to add new employee to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
						<input type="submit" class="btn btn-primary" value="Submit">
                        <a href="Employee_mgmt.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>