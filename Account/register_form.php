<script>var rBTC_Clicked = false;</script>
<form name="register" action="register.php" method="post">
  <input type="text" name="rBTC_Address" id="rBTC_Address" style="width: 280px" value="Your Bitcoin Wallet Address" onclick="if(true != rBTC_Clicked) document.getElementById('rBTC_Address').value=''; rBTC_Clicked=true;"> Bitcoin Address (doubles as user ID)<br />
  <input type="password" name="rPassword" id="rPassword" style="width: 280px" value=""> Password - OPTIONAL <a href="/FAQ.php#q_PW1">(why?)</a><br />
  <input type='hidden' name='returnURL' value<?php echo "=\"" . $_SERVER['REQUEST_URI'] . "\"" ?>>
  <input type="submit" name="Submit" value="Register!">
</form>
