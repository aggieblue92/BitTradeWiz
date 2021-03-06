<?php session_start(); include_once($_SERVER['DOCUMENT_ROOT'] . "/sql_includes.php");

/*************************************************\
   portfolio_select.php:
   
   Pretty easy. Select a portfolio. It will be given
   to you in GET form. Just set it to the
   $_SESSION['user']['ActivePortfolio']

\**************************************************/

$bErrMessage = "";
unset($_SESSION['user']['ActivePortfolio']);
$_SESSION['user']['ActivePortfolio']['ID'] = $_POST['acc_select'];

foreach($_POST as $key => $value) {
  echo "$key is $value<br />\n";
}

// Grab via SQL portfolio balance information
$connection = mysqli_connect($LOCALHOST, $USER, $SQL_PASSWORD, $DEFAULT_DB);

if(mysqli_connect_errno($connection)) {
  $bErrMessage .= mysqli_connect_error();
}

// Make sure that the user has access to the practice account requested:
$access = false;
if(0 == $_POST['acc_select']) {
$bErrMessage .= '0 selected';
  $access = true;
} else {
  $sql_query = "SELECT * FROM tbUserData WHERE UserID='" . $_SESSION['user']['ID'] . "'";
  $result = mysqli_query($connection, $sql_query);
  while($row = mysqli_fetch_array($result)) {
    $enabled_arrays = explode(',', $row['PracticeAcctIdList']);
    foreach($enabled_arrays as $value) {
      if($value == $_POST['acc_select']) {
        $access = true;
      }
    }
  }
}

// If success, ask SQL server for all the information:
if(true == $access) {
  $sql_query = "SELECT * FROM tbPracticeAccounts WHERE AcctID='" . $_SESSION['user']['ActivePortfolio']['ID'] . "'";
  $result = mysqli_query($connection, $sql_query);
  while($row = mysqli_fetch_array($result)) {
    // Load data...
    $_SESSION['user']['ActivePortfolio']['Shared'] = $row['Shared'];
    $_SESSION['user']['ActivePortfolio']['Balance_USD'] = $row['Balance_USD'] / $DIV_BY_AMOUNT;
    $_SESSION['user']['ActivePortfolio']['Balance_BTC'] = $row['Balance_BTC'] / $DIV_BY_AMOUNT;
    $_SESSION['user']['ActivePortfolio']['Settings'] = $row['Settings'];
    $_SESSION['user']['ActivePortfolio']['History'] = $row['History'];
    $_SESSION['user']['ActivePortfolio']['ValueIncrease'] = $row['ValueIncrease'];
    $_SESSION['user']['ActivePortfolio']['Pending'] = $row['Pending'];
    $_SESSION['user']['ActivePortfolio']['Value'] = $_SESSION['user']['ActivePortfolio']['Balance_USD'] + ($_SESSION['user']['ActivePortfolio']['Balance_BTC'] * getCurrentBTCPrice());
    
    foreach($_SESSION['user']['ActivePortfolio'] as $index => $value) {
      $_SESSION['user']['PracticeAcct'][$_SESSION['user']['ActivePortfolio']['ID']][$index] = $value;
    }
  }
} else {
  $bErrMessage += "Could not access SQL - user does not have permission for this account!";
}

// TODO: Via SQL, get portfolio balance information.
// Also: Do this via AJAX!!

if("" == $bErrMessage) {
  header("Location: " . $_POST['Return_URL']);
} else {
  echo "Error List:<br />\n$bErrMessage";
}
?>
