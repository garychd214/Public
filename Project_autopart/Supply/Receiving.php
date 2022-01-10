<?php session_start();?>
<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$EID = "";
$EID = $_SESSION['EID'];
$PartNo = $Qty = "";
$PartNo_err = $Qty_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate PartNo
    $input_PartNo = trim($_POST["PartNo"]);
    if(empty($input_PartNo)){
        $PartNo_err = "Please enter a Part Number.";
    } else{
        $PartNo = $input_PartNo;
    }
     
	
	// Validate Qty
    $input_Qty = trim($_POST["Qty"]);
    if(empty($input_Qty)){
        $Qty_err = "Please enter Quantity.";     
    } elseif(!ctype_digit($input_Qty)){
        $Qty_err = "Please enter a positive integer value.";
    } else{
		$Qty = $input_Qty;
    }
    
    
    // Check input errors before inserting in database
    if(empty($PartNo_err) && empty($Qty_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO inventory (Qty, PartNo) VALUES (?, ?)";
        $sql1 = "INSERT INTO Transaction (EID, PartNo, Qty, Date) VALUES (?, ?, ?, now())";
		$sql_update = "UPDATE inventory SET Qty = Qty + ? WHERE PartNo = ?";
		
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "is", $param_Qty, $param_PartNo);
            
            // Set parameters
            $param_PartNo = $PartNo;
            $param_Qty = $Qty;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Creating Transaction record
				if($stmt = mysqli_prepare($link, $sql1)){
					// Bind variables to the prepared statement as parameters
					mysqli_stmt_bind_param($stmt, "isi", $param_EID, $param_PartNo, $param_Qty);
					
					// Set parameters
					$param_PartNo = $PartNo;
					$param_Qty = $Qty;
					$param_EID = $EID;
					
					// Attempt to execute the prepared statement
					if(mysqli_stmt_execute($stmt)){
						// Records created successfully. Redirect to landing page
						header("location: http://localhost/project_autopart/Main.php");
						exit();
					} else{
						echo "(i)Something went wrong to create transaction. Please try again later.";
					}
				}
            } else{
					if($stmt = mysqli_prepare($link, $sql_update)){
						// Bind variables to the prepared statement as parameters
						mysqli_stmt_bind_param($stmt, "is", $param_Qty, $param_PartNo);
						
						// Set parameters
						$param_PartNo = $PartNo;
						$param_Qty = $Qty;
						
						// Attempt to execute the prepared statement
						if(mysqli_stmt_execute($stmt)){
							// Records created successfully. Creating Transaction record
							if($stmt = mysqli_prepare($link, $sql1)){
								// Bind variables to the prepared statement as parameters
								mysqli_stmt_bind_param($stmt, "isi", $param_EID, $param_PartNo, $param_Qty);
								
								// Set parameters
								$param_PartNo = $PartNo;
								$param_Qty = $Qty;
								$param_EID = $EID;
								
								// Attempt to execute the prepared statement
								if(mysqli_stmt_execute($stmt)){
									// Records created successfully. Redirect to landing page
									header("location: http://localhost/project_autopart/Main.php");
									exit();
								} else{
									echo "(u)Something went wrong to create transaction. Please try again later.";
								}
							}
						} else{
							echo "(u)Something went wrong. Please try again later.";
						}
					}
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
                        <h2>Receving Part</h2>
                    </div>
                    <p>Please fill this form and submit to receive part.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($Qty_err)) ? 'has-error' : ''; ?>">
                            <label>PartNo</label>
                            <input type="text" name="PartNo" class="form-control" value="<?php echo $PartNo; ?>">
                            <span class="help-block"><?php echo $Qty_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($PartNo_err)) ? 'has-error' : ''; ?>">
                            <label>Quantity</label>
                            <input type="text" name="Qty" class="form-control" value="<?php echo $Qty; ?>">
                            <span class="help-block"><?php echo $PartNo_err;?></span>
                        </div>

						<input type="submit" class="btn btn-primary" value="Submit">
                        <a href="http://localhost/project_autopart/Main.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>