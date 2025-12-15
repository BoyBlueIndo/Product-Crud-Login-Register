<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 420px;">

        <h4 class="text-center mb-4">Create Account</h4>

        <form action="{{ route('registerFunction') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input 
                    type="text" 
                    name="name" 
                    class="form-control" 
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    class="form-control" 
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    class="form-control" 
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    class="form-control" 
                    required
                >
            </div>

            <button class="btn btn-primary w-100">Register</button>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none">Already have an account?</a>
            </div>
        </form>

    </div>
</div>

</body>
</html>