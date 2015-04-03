<html>
<head></head>
<title>Reset password</title>
    <body>
<form action="http://localhost/vsm/api/forgotpassword/resetpassword.php" method="post">
    <input type="hidden" name="email"  value="<?php echo $_GET['email']?>"  >
    <p><label>New Password</label><input type="password" name="password"   ></p>
    <p><label>Confirm Password </label><input type="password" name="confirm password"  ></p>
    <p><input type="submit" value="Reset"></p>
</form>
    </body>
</html>