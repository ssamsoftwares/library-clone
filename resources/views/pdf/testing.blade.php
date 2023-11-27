<!DOCTYPE html>
<html>
<head>
  <title>Copy to Clipboard</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="m-4">
                <h4>https://www.k3library.com/ K3Library Password Reset</h3>
            </div>
            <div class="card-body">
                <p>Hello <b>NITa</b>,</p>
                <p>We received a request to reset the password for your account at https://www.k3library.com/ K3Library.</p>
                <p>If you did not request this change, please ignore this email. No further action is needed.</p>

                <p>However, if you did request a password reset, please use the link below to set a new password:</p>

                <p><a href="#" target="_blank">Reset Your Password</a></p>

                {{-- <p>This link will expire in [expiration_time], so please reset your password as soon as possible.</p> --}}

                <p>If you're having trouble clicking the password reset link, you can copy and paste the following URL into your browser:</p>
                <p id="copyText"></p>
                 <button id="copyButton">Copy Url</button>

                <p>Thank you,</p>
                <p>The K3Library Team</p>
                <p>Website: <a href="https://www.k3library.com/ " target="_blank">https://www.k3library.com/ </a></p>
            </div>
        </div>
    </div>

  <script>
    $(document).ready(function() {
      $('#copyButton').click(function() {
        const textToCopy = $('#copyText').text();
        navigator.clipboard.writeText(textToCopy)
          .then(() => {
            console.log(`Copied "${textToCopy}" to clipboard`);
          })
          .catch((err) => {
            console.error('Could not copy text: ', err);
          });
      });
    });
  </script>
</body>
</html>

