<?php

    // Configuration
    require("../includes/config.php");

    // If user reached page via GET 
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // Render buy_form.php
        render("buy_form.php", ["title" => "Sell"]);
    }
    // Else if user reached page via POST (as by submitting a form via POST)
    else if  ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Check if symbol field is empty
        if (empty($_POST["symbol"]))
        {
            apologize("You must enter a stock symbol.");
        }
        
        // Check of shares field is empty
        if (empty($_POST["shares"]))
        {
            apologize("You must enter the number of shares you wish to buy.");
        }
        
        // Check if user input a positive integer for shares
        if (!preg_match("/^\d+$/", $_POST["shares"]))
        {
            apologize("You must enter a positive integer for shares.");
        }
        
        // Look up information for inputted symbol
        $stock = lookup($_POST["symbol"]);
        
        // Check to see if symbol exists
        if ($stock === false)
        {
            apologize("Please enter a valid stock.");
        }
        
        // Get current cash value from user
        $cash = CS50::query("SELECT cash FROM users WHERE id = ?", $_SESSION["id"]);
        
        // get the value of stocks user wants to buy
        $value = $stock["price"] * $_POST["shares"];
        
        // Check to see if value of stocks exceed the cash user has
        if ($cash[0]["cash"] < $value)
        {
            // Apologize if not enough cash
            apologize("Sorry. You don't have enough cash.");
        }
        else
        {
            // Update portfolio if buying is successful
            CS50::query("INSERT into portfolio (user_id, symbol, shares) VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE shares = shares + ?", $_SESSION["id"], strtoupper($_POST["symbol"]), $_POST["shares"], $_POST["shares"]);
            
            // Update cash if buying is successful
            CS50::query("UPDATE users SET cash = cash - ? WHERE id = ?", $value, $_SESSION["id"]);
            
            // Update history if buying is successful
            $transaction = 'BUY';
            CS50::query("INSERT INTO history (user_id, transaction, symbol, shares, price, timestamp) VALUES (?, ?, ?, ?, ?, NOW())", $_SESSION["id"], $transaction, strtoupper($_POST["symbol"]), $_POST["shares"], $stock["price"]);
        }    
        
        // Redirect user back to user's portfolio after buying
        redirect("index.php");
    }    
?>    
