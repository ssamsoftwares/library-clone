<!doctype html>
<html lang="en">

<head>
    <title>Password Assigned</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="m-4">
                <h4>[k3library] Asign the password</h3>
            </div>
            <div class="card-body">
                <p>Hello <b>{{$data['name']}}</b>,</p>
                <p>Your password for the K3 library has been assigned successfully. Here are the details:</p>

                <ul>
                    <li><strong>Email:</strong> {{$data['email']}}</li>
                    <li><strong>Password:</strong> {{$data['password']}}</li>
                </ul>

                <p>You can now use these credentials to log in to the K3 library.</p>
                <p>Login Url <a href="{{route('login')}}">Click here</a></p>

                <p>Thank you,</p>
                <p>The K3 Library Team</p>
                <p>Website : <a href="https://www.k3library.com/" target="_blank">https://www.k3library.com/</a></p>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
