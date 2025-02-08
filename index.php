<?php

  $path = $_SERVER['REQUEST_URI'] ?? '';

  $path = explode('?', $path)[0];
  $path = explode('#', $path)[0];

  $path = trim($path);
  $path = trim($path, '/');

  $ipAndPort = null;
  $landingPage = false;

  if (preg_match('/^(?:\d{1,3}\.){3}\d{1,3}(?::\d{1,5})?$/', $path)) {
    $ipAndPort = $path;
  }
  else if (preg_match('/^((?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,})((?::\d{1,5})?)$/', $path, $matches)) {
    $domain = $matches[1];
    $port = $matches[2];
    $ip = gethostbyname($domain);
    if ($ip == $domain) {
      $landingPage = true;
    }
    else {
      $ipAndPort = $ip . $port;
    }
  }
  else {
    $landingPage = true;
  }

?><!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?php echo $landingPage ? "Steam Connect Web Service" : "Connect to " . $path; ?></title>
  <link rel="icon" type="image/png" href="./favicon.png">

  <link href="./css/bootstrap/bootstrap.min.css" rel="stylesheet">
  <link href="./css/style.css" rel="stylesheet">

</head>
<body>
  <?php if ($landingPage) { ?>

    <div class="container">
      <div class="card" style="width: 36rem;">

        <h1 class="card-header h2">Steam Connect Web Service</h1>

        <div class="card-body">

          <p class="card-text">To create a link that lets players automatically connect to a Steam game server, create a URL in the following format:</p>

          <div>
            <input type="text" class="form-control" style="font-family: var(--bs-font-monospace), monospace;" aria-label="Connect to the server" value="https://steam-connect.zaarthras.de/<your-ip:your-port>" readonly>
          </div>

        </div>

        <div class="card-footer">
          This website does not collect any personal data.
        </div>

      </div>
    </div>

  <?php } else { ?>

    <div class="container">
      <div class="card" style="width: 32rem;">

        <h1 class="card-header h2">Join the server manually</h1>

        <div class="card-body">

          <p class="card-text">If your Browser is unable to automatically connect to steam, use the button or the connect command below to manually connect to the server. You can close this website after.</p>

          <div class="mb-3">
            <div class="d-grid gap-2">
              <a href="steam://connect/<?php echo $ipAndPort; ?>" class="btn btn-primary">Connect to <code><?php echo $path; ?></code></a>
            </div>
          </div>

          <div>
            <label class="form-label"><small>If the button does not work, copy this command into your game's console:</small></label>
            <div class="input-group">
              <input type="text" class="form-control" id="console-command" aria-label="Connect to the server" value="connect <?php echo $path; ?>" readonly>
              <button class="btn btn-secondary" id="console-command-copy">Copy</button>
            </div>
          </div>

        </div>

        <div class="card-footer">
          This website does not collect any personal data.
        </div>

      </div>
    </div>

    <!--<script src="./js/bootstrap/bootstrap.bundle.min.js"></script>-->
    <script>

      let steamLink = "steam://connect/<?php echo $ipAndPort; ?>";

      const copyButton = document.getElementById('console-command-copy');

      // Function to copy the text to the clipboard
      copyButton.addEventListener('click', () => {

        const input = document.getElementById('console-command');

        input.select();
        input.setSelectionRange(0, 99999); // For mobile devices

        try {
          // Execute the copy command
          navigator.clipboard.writeText(input.value);
          copyButton.textContent = "Copied!"
          setTimeout(() => { copyButton.textContent = "Copy" }, 2000);
        }
        catch (err) {}

      });

      // Try opening the Steam link
      window.location.href = steamLink;

      let windowNew = true;
      let popupOpen = false;

      setTimeout(() => {
        windowNew = false;
      }, 100);

      window.onblur = () => {
        if (windowNew) {
          popupOpen = true;
        }
      }

      window.onfocus = () => {
        if (popupOpen) {
          window.close();
          popupOpen = false;
        }
        windowNew = false;
      }

    </script>

  <?php } ?>

</body>
</html>
