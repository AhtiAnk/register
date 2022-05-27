<!-- E-mail ja kasutajanimi ei ole tõstutundlikud -->

<?php
  include "config/database.php" ?>

<?php
  session_start();
  $username = $email = $logEmail = $password = $logPassword = $password2 = "";
  $nameError = $emailError = $logEmailError = $passwordError = $logPasswordError = $matchError = $wrongDataError = "";

  if(isset($_POST["submit"])){

    //switch meetod, et kas logimine või registreerimine
    switch ($_POST["form"]) {
      //Registreerimis vorm
      case "reg":

        //Lahtrite valideerimine
        if(empty($_POST["email"])) {
          $emailError = "Sisesta email";
        } else {
          $email = strtolower(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
          //Kontrolli kas selline e-mail on juba olemas
          $sql = "SELECT email FROM users";
          $result = mysqli_fetch_all(mysqli_query($conn, $sql));
          
          foreach($result as $emailArray) {
            foreach($emailArray as $dbEmail) {
              if($dbEmail === $email) {
                $emailError = "Sellise e-mailiga kasutaja on juba olemas";
              }
            }   
          }
        }

        if(empty($_POST["username"])) {
          $nameError = "Sisesta kasutajanimi";
        } else {
          $username = strtolower(filter_input(INPUT_POST, "username", FILTER_SANITIZE_FULL_SPECIAL_CHARS));

          //Kontrolli kas selline kasutajanimi on olemas
          $sql = "SELECT username FROM users";
          $result = mysqli_fetch_all(mysqli_query($conn, $sql));
          
          foreach($result as $usernameArray) {
            foreach($usernameArray as $dbUsername) {
              if($dbUsername === $username) {
                $nameError = "Sellise kasutajanimega kasutaja on juba olemas";
              } 
            }  
          }
        }

        if(empty($_POST["password"])) {
          $passwordError = "Sisesta parool";
        } else {
          $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
        }

        if(empty($_POST["password2"])) {
          $passwordError = "Sisesta parool";
        } else {
          $password2 = password_hash($_POST["password2"], PASSWORD_BCRYPT);
        }

        if($_POST["password2"] !== $_POST["password"]) {
          $matchError = "Paroolid ei kattu";
        }

        if(empty($emailError) && empty($nameError) && empty($passwordError) && empty($matchError)) {
          $sql = "INSERT INTO users(username, password, email) VALUES('$username', '$password', '$email')";

          if (mysqli_query($conn, $sql)) {
            header('Location: success.php');
          } else {
            echo 'Error: ' . mysqli_error($conn);
          }
        }

        break;

      //Logimisvorm  
      case "log":

        //Lahtrite valideerimine
        if(empty($_POST["logEmail"])) {
          $logEmailError = "Sisesta email";
        } else {
          $logEmail = strtolower(filter_input(INPUT_POST, "logEmail", FILTER_SANITIZE_EMAIL));
        }

        if(empty($_POST["logPassword"])) {
          $logPasswordError = "Sisesta parool";
        } else {
          $logPassword = $_POST["logPassword"];
        }

        if(empty($logEmailError) && empty($logPasswordError)) {
          $sql = "SELECT email, password FROM users WHERE email='$logEmail'";
          $result = mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC);
          //Kotrolli parooli ja emaili andmebaasist
          if (!empty($result) && $logEmail === $result[0]["email"] && password_verify($logPassword, $result[0]["password"])) {
            header('Location: success.php');
          } else {
            $wrongDataError = "E-mail või parool on vale";
          }
        }
        break;
    } 
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="res/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js">
    </script>
    <script src="res/script.js"></script>
    <title>Register</title>
</head>
<body>

  <div class="box">
    <!-- Registreerimise ja logimise vahel vahetamine -->
    <ul class="tabs">
      <li id="registerLink" data-tab-target="#registerForm">Registreeri</li> 
      <span> | </span>
      <li id="loginLink" data-tab-target="#loginForm" class="active">Logi sisse</li>
    </ul>
    
    <!-- Login form, on aktiivne lehe esmakordsel laadimisel -->
    <div id="loginForm" data-tab-content class="active">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <input type="hidden" name="form" value="log" />
        <div class="textinput">

            <input
              class="<?php echo $logEmailError ? "is-invalid" : null; ?>" 
              type="email" 
              name="logEmail" 
              placeholder="E-mail"
              value="<?php if(isset($_POST["logEmail"])) echo $_POST["logEmail"]; ?>"
               />
            <p><?php echo $logEmailError; ?></p>

            <input 
              class="<?php echo $logPasswordError ? "is-invalid" : null; ?>"
              type="password"
              name="logPassword"
              placeholder="Parool"
            />
            <p><?php echo $logPasswordError; ?></p>
            <p><?php echo $wrongDataError; ?></p>

        </div>  
        <br />

        <div class="submitbutton">
          <input type="submit" name="submit" value="Login">
        </div>

      </form>
    </div>

    <!-- Register form -->
    <div id="registerForm" data-tab-content>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <input type="hidden" name="form" value="reg" />
        <div class="textinput">

            <input 
              class="<?php echo $emailError ? "is-invalid" : null; ?>"
              type="email" 
              name="email" 
              placeholder="E-mail"
              value="<?php if(isset($_POST["email"])) echo $_POST["email"]; ?>" 
            />
            <p><?php echo $emailError; ?></p>

            <input
              class="<?php echo $nameError ? "is-invalid" : null; ?>"
              type="text"
              name="username"
              placeholder="Kasutajanimi"
              value="<?php if(isset($_POST["username"])) echo $_POST["username"]; ?>"
            />
            <p><?php echo $nameError; ?></p>

            <input
              class="<?php echo $passwordError ? "is-invalid" : null; ?>"
              type="password"
              name="password"
              placeholder="Parool"
            />
            <p><?php echo $passwordError; ?></p>

            <input
              class="<?php echo $passwordError ? "is-invalid" : null; ?>"
              type="password"
              name="password2"
              placeholder="Korda parooli"
            />
            <p><?php echo $passwordError; ?></p>
            <p><?php echo $matchError; ?></p>

            <br />
        </div>

        <div class="submitbutton">    
          <input type="submit" name="submit" value="Registreeru">
        </div>

      </form>
    </div>
  </div>  
</body>
</html>