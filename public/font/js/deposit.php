<?php

    // Configuration
    require("../includes/config.php");

    // If user reached page via GET 
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    { 
        // Render sell_form.php
        render("deposit_form.php", ["title" => "Deposit"]);
    }
    
    // Else if user reached page via POST (as by submitting a form via POST)
    else if  ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // If symbol field is empty, apologize
        if (empty($_POST["deposit"]))
        {
            apologize("You must enter an amount of cash to deposit.");
        }
        
        // Check if user input a positive integer for shares
        else if (!preg_match("/^\d+$/", $_POST["deposit"]))
        {
            apologize("You must enter a positive whole number amount. No change please!");
        }
        
        else if ($_POST["deposit"] > 5000)
        {
            apologize("You can only deposit a maximum of $5000.");
        }
        
        else
        {
            // Update cash by adding cash deposited to current cash
            CS50::query("UPDATE users SET cash = cash + ? WHERE id = ?", $_POST["deposit"], $_SESSION["id"]);
        }
        
        // Redirect to home page
        redirect("index.php");
    }    
?>    
