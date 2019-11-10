<?php include "includes/config.php";?>
<?php include "includes/classes/Account.php";?>
<?php include "includes/classes/Constants.php";

$account = new Account($con);

function getInputValue($name) {
    if(isset($_POST[$name])){
        echo $_POST[$name];
    }
}
?>
<?php include "includes/handlers/register-handler.php"?>
<?php include "includes/handlers/login-handler.php"?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Slotify</title>
    <link rel="stylesheet" href="assets/css/register.css" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="assets/js/register.js"></script>
</head>
<body>

<?php

if(isset($_POST['registerButton'])){
    echo '<script>
    $(document).ready(function () {
        $("#loginForm").hide();
        $("#registerForm").show();
    });
</script>';
} else {
    echo '<script>
    $(document).ready(function () {
        $("#loginForm").show();
        $("#registerForm").hide();
    });
    </script>';
}

?>


<div id="background">
    <div id="loginContainer">
    <div id="inputContainer">
        <form id="loginForm" action="register.php" method="post">
            <h2>Login to your account</h2>
            <p>
                <?php echo $account->getError(Constants::$loginFailed)?>
                <label for="loginUsername">Username</label>
        <input id="loginUsername" name="loginUsername" type="text" required value="<?php getInputValue('loginUsername')?>"></p>

        <p>
            <label for="loginPassword">Password</label>

            <input id="loginPassword" name="loginPassword" type="password" required></p>

            <button type="submit" name="loginButton">Login</button>

            <div class="hasAccountText">
                <a href="#"><span id="hideLogin">Don't have an account yet? Signup here.</span></a>
            </div>
        </form>



        <form id="registerForm" action="register.php" method="post">
            <h2>Create your free account</h2>
            <p>
                <?php echo $account->getError(Constants::$usernameCharacters)?>
                <?php echo $account->getError(Constants::$usernameTaken)?>
                <label for="username">Username</label>
                <input id=username" name="username" type="text" required value="<?php getInputValue('username')?>"></p>

            <p>
                <?php echo $account->getError(Constants::$firstNameCharacters)?>
                <label for="firstName">First name</label>
                <input id=firstName" name="firstName" type="text" required value="<?php getInputValue('firstName')?>"></p>
            <p>
                <?php echo $account->getError(Constants::$lastNameCharacters)?>
                <label for="lastName">Last name</label>
                <input id=lastName" name="lastName" type="text" required value="<?php getInputValue('lastName')?>"></p>

            <p>
                <?php echo $account->getError(Constants::$emailsDoNotMatch)?>
                <?php echo $account->getError(Constants::$emailInvalid)?>
                <?php echo $account->getError(Constants::$emailTaken)?>
                <label for="email">Email</label>
                <input id=email" name="email" type="email" required value="<?php getInputValue('email')?>"></p>

            <p>
                <label for="email2">Confirm email</label>
                <input id=email2" name="email2" type="email" required value="<?php getInputValue('email2')?>"></p>

            <p>
                <?php echo $account->getError(Constants::$passwordsDoNotMatch)?>
                <?php echo $account->getError(Constants::$passwordNotAlphanumeric)?>
                <?php echo $account->getError(Constants::$passwordCharacters)?>
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required></p>

            <p>
                <label for="password2">Confirm Password
                <input id="password2" name="password2" type="password" required></p>

            <button type="submit" name="registerButton">Sign Up</button>

            <div class="hasAccountText">
                <a href="#"><span id="hideRegister">Already have an account? Login here</span></a>
            </div>
        </form>
    </div>
        <div id="loginText">
            <h1>Get great music, right now!</h1>
            <h2>Listen to loads of songs for free</h2>
            <ul>
                <li>Discover music you like</li>
                <li>Create your own playlist</li>
                <li>Follow artists to keep up to date</li>
            </ul>
        </div>
    </div>
    </div>

</body>
</html>