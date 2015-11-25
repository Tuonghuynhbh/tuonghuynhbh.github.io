<?php

    // Configuration
    require("../includes/config.php"); 
    
    // Get user's username and cash from users table
    $user = CS50::query("SELECT username, cash FROM users WHERE id = ?", $_SESSION["id"]);
    
    // Get user's stocks from portfolio table
    $rows = CS50::query("SELECT symbol, shares FROM portfolio WHERE user_id = ?", $_SESSION["id"]);
    
    // Initiate positions to store uder's stock information
    $positions = [];
    foreach ($rows as $row)
    {
        // Look up stock information
        $stock = lookup($row["symbol"]);
        
        if ($stock !== false)
        {
            // Store user's stock informtation into positions
            $positions[] = [
                "name" => $stock["name"],
                "symbol" => $stock["symbol"],
                "shares" => $row["shares"],
                "price" => $stock["price"],
                "value" => number_format($stock["price"] * $row["shares"], 2)
            ];
        }
    }
    
    // render portfolio form
    render("portfolio.php", ["title" => "Portfolio", "user" => $user, "positions" => $positions]);
?>
