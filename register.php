<!DOCTYPE html>
<?php
  include("includes/config.php");
  include("includes/classes/Account.php");
  include("includes/classes/Constants.php");

  $account = new Account($con);

  include("includes/handlers/register-handler.php");
  include("includes/handlers/login-handler.php");

  function getInputValue($name) {
    if (isset ($_POST[$name])) {
      echo $_POST[$name];
    }
  }
?>

<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Welcome to Clonify</title>


    <link rel="stylesheet" type="text/css" href="assets/css/register.css">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script src="assets/js/register.js"></script>
  </head>
  <body>
    <?php
    if (isset($_POST['registerButton'])) {
      echo '<script>
          $(document).ready(function() {
            $("#loginForm").hide();
            $("#registerForm").show();
          });
        </script>';
    } else {
      echo '<script>
          $(document).ready(function() {
            $("#loginForm").show();
            $("#registerForm").hide();
          });
        </script>';
    }
    ?>


    <div id="background">
      <div id="loginContainer">
        <div id="inputContainer">
          <form id="loginForm" action="register.php" method="POST">
            <h2>Login to your account</h2>
            <p>
              <?php echo $account->getError(Constants::$loginFailed); ?>
              <label for="loginUsername">Username</label>
              <input id="loginUsername" name="loginUsername" type="text" placeholder="Username" value="<?php getInputValue('loginUsername') ?>" required>
            </p>
            <p>
              <label for="loginPassword">Password</label>
              <input id="loginPassword" name="loginPassword" type="password" placeholder="Your Password" required>
            </p>

            <button type="submit" name="loginButton">Log In</button>

            <div class="hasAccountText">
              <span>Don't have an account yet? Signup <span id="hideLogin">here</span>.</span>
            </div>

          </form>


          <form id="registerForm" action="register.php" method="POST">
            <h2>Create your free account</h2>
            <p>
              <?php echo $account->getError(Constants::$usernameLength); ?>
              <?php echo $account->getError(Constants::$usernameTaken); ?>
              <label for="username">Username</label>
              <input id="username" name="username" type="text" placeholder="Username" value="<?php getInputValue('username') ?>" required>
            </p>

            <p>
              <?php echo $account->getError(Constants::$firstNameLength); ?>
              <label for="firstName">First Name</label>
              <input id="firstName" name="firstName" type="text" placeholder="First Name" value="<?php getInputValue('firstName') ?>" required>
            </p>

            <p>
              <?php echo $account->getError(Constants::$lastNameLength); ?>
              <label for="lastName">Last Name</label>
              <input id="lastName" name="lastName" type="text" placeholder="Last Name" value="<?php getInputValue('lastName') ?>" required>
            </p>

            <p>
              <?php echo $account->getError(Constants::$emailsDontMatch); ?>
              <?php echo $account->getError(Constants::$emailInvalid); ?>
              <?php echo $account->getError(Constants::$emailTaken); ?>
              <label for="email">Email Address</label>
              <input id="email" name="email" type="email" placeholder="Test@gmail.com" value="<?php getInputValue('email') ?>" required>
            </p>

            <p>
              <label for="email2">Confirm Email Address</label>
              <input id="email2" name="email2" type="email" placeholder="Test@gmail.com" value="<?php getInputValue('email2') ?>" required>
            </p>



            <p>
              <?php echo $account->getError(Constants::$passwordCharacters); ?>
              <?php echo $account->getError(Constants::$passwordNotAlphaNumeric); ?>
              <?php echo $account->getError(Constants::$passwordsDoNotMatch); ?>
              <label for="password">Password</label>
              <input id="password" name="password" type="password" placeholder="Your Password" required>
            </p>

            <p>
              <label for="password2">Confirm Password</label>
              <input id="password2" name="password2" type="password" placeholder="Your Password" required>
            </p>

            <button type="submit" name="registerButton">Sign Up</button>
            <div class="hasAccountText">
              <span>Already have an account? Login <span id="hideRegister">here</span>.</span>
            </div>

          </form>
        </div>
          <div id="loginText">
            <h1>Get great music, right now.</h1>
            <h2>Listen to loads of songs for free.</h2>
            <ul>
              <li>Discover music you'll fall in love with</li>
              <li>Create your own playlists</li>
              <li>Follow artists to keep up to date</li>
            </ul>
          </div>


      </div>
    </div>

  </body>
</html>
