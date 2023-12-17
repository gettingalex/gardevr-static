<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/img/logo.ico">
    <title>Garde VR | Contact</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://www.google.com/recaptcha/api.js"></script>
</head>

<?php

$errors = [];
$errorMessage = '';

// Google reCAPTCHA API key configuration
$secret = 'your secret key';

if (!empty($_POST)) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $Interest = $_POST['interest'];
    $message = $_POST['message'];
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $recaptchaUrl = "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$recaptchaResponse}";
    $verify = json_decode(file_get_contents($recaptchaUrl));

    if (!$verify->success) {
      $errors[] = 'Recaptcha failed';
    }

    if (empty($name)) {
        $errors[] = 'Name is empty';
    }

    if (empty($email)) {
        $errors[] = 'Email is empty';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is invalid';
    }
    if (empty($interest)) {
        $errors[] = 'Interest is empty';
    }

    if (empty($message)) {
        $errors[] = 'Message is empty';
    }


    if (!empty($errors)) {
        $allErrors = join('<br/>', $errors);
        $errorMessage = "<p style='color: red;'>{$allErrors}</p>";
    } else {
        $toEmail = 'fermetroissaumons@gmail.com';
        $emailSubject = 'New email from your contact form';
        $headers = ['From' => $email, 'Reply-To' => $email, 'Content-type' => 'text/html; charset=utf-8'];

        $bodyParagraphs = ["Name: {$name}", "Email: {$email}", "Interest: {$interest}" "Message:", $message];
        $body = join(PHP_EOL, $bodyParagraphs);

        if (mail($toEmail, $emailSubject, $body, $headers)) {
            header('Location: thank-you.html');
        } else {
            $errorMessage = "<p style='color: red;'>Oops, something went wrong. Please try again later</p>";
        }
    }
}

?>

<body>

<!-- Section 1: Header -->
<div class="header">
    <img src="img/logo.png" alt="Company Logo" class="logo">
    <h2>Protégé votre investissement.</h2>
    <a href="https://maps.app.goo.gl/Vy8t7u1TPhY8dFjw9"><p>Situé à Saint-Jean-Port-Joli<br>3km de sorti 414 sur l'autoroute 20</p></a>
    <button class="action-btn">Reservé votre espace</button>
</div>

<!-- Section 2L Contact -->
<div class="contact">
<form action="/form.php" method="post" id="contact-form">
    <h2>Réservé votre espace maintenant <br> pour l'hiver 2024-25</h2>

    <?php echo((!empty($errorMessage)) ? $errorMessage : '') ?>
    <p>
      <label>Nom:</label>
      <input name="name" type="text"/>
    </p>
    <p>
      <label>Courriel</label>
      <input style="cursor: pointer;" name="email" type="text"/>
    </p>
    <p>
      <label>Interest</label>
      <input style="cursor: pointer;" name="Interest" type="text"/>

      <p>Intéressé pour: </p>
        <p><input type="radio" name="Interest" value="25">Roulotte de moins de 25'</p>
    <p><input type="radio" name="Interest" value="35">Tarif par roulotte de 26' à 35'</p>
    <p><input type="radio" name="Interest" value="Agricole">Machinerie Agricole</p>

    <p>
      <label>Message:</label>
      <textarea name="message"></textarea>
    </p>

    <p>
      <button
        class="g-recaptcha"
        type="submit"
        data-sitekey="your site key"
        data-callback='onRecaptchaSuccess'
      >
        Submit
      </button>
    </p>
  </form>
</div>
  <script src="//cdnjs.cloudflare.com/ajax/libs/validate.js/0.13.1/validate.min.js"></script>
  <script>
      const constraints = {
          name: {
              presence: {allowEmpty: false}
          },
          email: {
              presence: {allowEmpty: false},
              email: true
          },
          interest: {
              presence: {allowEmpty: false},
          },
          message: {
              presence: {allowEmpty: false}
          }
      };

      const form = document.getElementById('contact-form');

      form.addEventListener('submit', function (event) {
          const formValues = {
              name: form.elements.name.value,
              email: form.elements.email.value,
              interest: form.elements.type.value,
              message: form.elements.message.value
          };

          const errors = validate(formValues, constraints);

          if (errors) {
              event.preventDefault();
              const errorMessage = Object
                  .values(errors)
                  .map(function (fieldValues) {
                      return fieldValues.join(', ')
                  })
                  .join("\n");

              alert(errorMessage);
          }
      }, false);

      function onRecaptchaSuccess () {
          document.getElementById('contact-form').submit()
      }
  </script>


<!-- <Section 5: Footer -->
<footer>
    <div class="footer-logo">
        <p>Ferme Trois-Saumons inc.</p>
    </div>
    <a href="#">Link</a>
</footer>


</body>
</html>
