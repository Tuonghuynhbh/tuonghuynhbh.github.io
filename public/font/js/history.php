<?php

    // Configuration
    require("../includes/config.php");  

	// Store history information in $history
    $history = CS50::query("SELECT * FROM history WHERE user_id = ?", $_SESSION["id"]);
    
    // Render history form
    render("history_form.php", ["title" => "History", "history" => $history]);
 ?>
 
