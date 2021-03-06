<?php
  // INITIATE DATABASE - only used to INITIATE the DATABASE.
  $connection = mysqli_connect($LOCALHOST, $USER, $SQL_PASSWORD);
  
  if(mysqli_connect_errno($con)) {
    echo "Failed to connect to MySQL server: " . mysqli_connect_error() . "<br />\n";
  }
  
  // Create database
  $sql_query = "CREATE DATABASE dbUsers"; // TODO later: change name against hackers?
  if(mysqli_query($connection, $sql_query)) {
    echo "Database created successfully!<br />\n";
  } else {
    echo "Failed to create database: " . mysqli_error($connection) . "<br />\n";
  }
  
  $connection = mysqli_connect($LOCALHOST, $USER, $SQL_PASSWORD, $DEFAULT_DB);
  
  // Drop tbUserData Table
  $sql_query = "DROP TABLE tbUserData";
  if(mysqli_query($connection, $sql_query)) {
    echo "Table dropped successfully!<br />\n";
  } else {
    echo "Failed to drop table: " . mysqli_error($connection) . "<br />\n";
  }
  
  // Create tbUserData Table
  $sql_query = "CREATE TABLE tbUserData(PID INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(PID), UserID CHAR(35), Password CHAR(50), Balance INT,  Balance_NT INT, PracticeAcctIdList CHAR(32), History TEXT, Incentives TEXT)";
  if(mysqli_query($connection, $sql_query)) {
    echo "Table created successfully!<br />\n";
  } else {
    echo "Failed to create table: " . mysqli_error($connection) . "<br />\n";
  }
  
  // Drop tbPracticeAccounts Table
  $sql_query = "DROP TABLE tbPracticeAccounts";
  if(mysqli_query($connection, $sql_query)) {
    echo "Table dropped successfully!<br />\n";
  } else {
    echo "Failed to drop table: " . mysqli_error($connection) . "<br />\n";
  }
  
  // Create tbPracticeAccounts Table
  $sql_query = "CREATE TABLE tbPracticeAccounts(PID INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(PID), AcctID INT, Shared CHAR(1), Balance_USD CHAR(80), Balance_BTC CHAR(80), Settings CHAR(50), History CHAR(200), Pending CHAR(72), ValueIncrease INT)";
  if(mysqli_query($connection, $sql_query)) {
    echo "Table created successfully!<br />\n";
  } else {
    echo "Failed to create table: " . mysqli_error($connection) . "<br />\n";
  }
  
  // Create a dummy account, it'll be yours don't worry.
  $sql_query = "INSERT INTO tbUserData (UserID, Password, Balance, Balance_NT, Incentives) ";
  $sql_query .= "VALUES ('1grQWRRH98qyDh9jwtwRYkHd5zbdgYBWd', '', '0', '1000000', 'HALFMATCH')";
  if(mysqli_query($connection, $sql_query)) {
    echo "Query: $sql_query successfully completed!<br />\n";
  } else {
    echo "Query: $sql_query failed: " . mysqli_error($connection) . "<br />\n";
  }

  // Create our ZERO practice account...
  $sql_query = "INSERT INTO tbPracticeAccounts (AcctID, Shared, Balance_USD, Balance_BTC, ValueIncrease) ";
  $sql_query .= "VALUES (0, 'y', '125060000', '15000000', 0)";
  if(mysqli_query($connection, $sql_query)) {
    echo "Query: $sql_query successfully completed!<br />\n";
  } else {
    echo "Query: $sql_query failed: " . mysqli_error($connection) . "<br />\n";
  }
  
  // And finally, clear our price/trade databases...
  $files = array("/data/buy_posted.dat", "/data/buy.dat", "/data/sell.dat", "/data/sell_posted.dat", "/data/latency.dat", "/data/operation_queue.dat");
  file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/data/nextIdValues.dat", "0\n0");
  echo "<br />\nCleared " . $_SERVER['DOCUMENT_ROOT'] . "/data/nextIdValues.dat<br />\n";
  foreach($files as $ext) {
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . $ext, "");
    echo "Cleared " . $_SERVER['DOCUMENT_ROOT'] . $ext . "<br />\n";
  }
  
  mysqli_close($connection);
?>
