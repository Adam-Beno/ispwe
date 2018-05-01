<?php
  include_once "password-generator.php";
  include "config.php";

  // Sanitize the domain string
  $clean_domain = preg_replace("/[^a-zA-Z0-9]+/", "", $_POST['domain_name']);

  // Define errors
  $errors = [];

  // Check if domain name is taken
  if (file_exists($subdomains . $clean_domain)) {
      $errors[] = "Domain name <strong>" . $clean_domain . "</strong> is already taken";
  }

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
      $errors[] = "Failed to connect to the database, please try again later...";
  }

  // Generate FTP password
  $generated_password = generateStrongPassword();

  // Check for errors
  if (count($errors) == 0) {
      // Compose subdomain path
      $domain_dir_path = $subdomains.$clean_domain."/public";

      // Create subdomain folder
      if (!mkdir($domain_dir_path, 0777, true)) {
          $errors[] = "Failed to create a subdirectory for your domain name, please try again later...";
      }

      // Add initial index.html file in the subdomain folder for testing purposes
      file_put_contents($domain_dir_path . "/index.html", file_get_contents("./template.html"));

      // Prepare SQL
      $sql = "INSERT INTO users (userid, password, uid, gid, homedir, shell) VALUES ('". $clean_domain;
      $sql += "','" . $generated_password . "', 5,6,'" . $domain_dir_path . "','bin/bash'";

      // Exectute SQL
      if ($conn->query($sql) !== true) {
          $errors[] = "Failed to create a database record, please try again later";
          // In case of failure, don't forget to remove the directory
          rmdir($subdomains.$clean_domain);
      }
  }
  // Close connection
  $conn->close();
?>
<!doctype html>
<html lang="en">
  <head>
    <title>ISPWE Hosting</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" href="./styles.css">
  </head>
  <body>
  <div class="container">
      <header class="header clearfix">
        <h3 class="text-muted">ISPWE Hosting</h3>
      </header>

      <main role="main">
        <hr />
        <?php if (count($errors) > 0): ?>
          <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Whoops, something went wrong!</h4>
            <p>There seems to be something wrong with your request, please see the list below for specific errors.</p>
            <hr>
            <ul>
              <?php foreach ($errors as $error): ?>
                <li><?php echo $error;?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php else: ?>
          <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Well done!</h4>
            <p>Aww yeah, you just created your public web repository, see the details below to know how to connect.</p>
            <hr />
            <p>We've put a default file in your root directory so you can test the functionality of the hosting, feel free to delete it.</p>
          </div>
          <br />
          <h3>WWW</h3>
          <hr />
          <dl class="row">
            <dt class="col-sm-3">Public domain</dt>
            <dd class="col-sm-9"><a href="http://<?php echo $clean_domain . "." . $localdomain ?>"><?php echo $clean_domain . "." . $localdomain ?></a></dd>
          </dl>
          <h3>FTP</h3>
          <hr />
          <dl class="row">
            <dt class="col-sm-3">Server name</dt>
            <dd class="col-sm-9"><a href="<?php echo $ftpserver; ?>"><?php echo $ftpserver; ?></a></dd>

            <dt class="col-sm-3">Username</dt>
            <dd class="col-sm-9"><?php echo $clean_domain ?></dd>

            <dt class="col-sm-3">Password</dt>
            <dd class="col-sm-9"><?php echo $generated_password ?></dd>
          </dl>
        <?php endif; ?>
      </main>
      <br />
      <footer class="footer">
        <p>© Company 2017</p>
      </footer>

    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  </body>
</html>