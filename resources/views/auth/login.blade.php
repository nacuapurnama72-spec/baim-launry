<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Laundry IMZ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body style="background:#f4f7fa">
<div class="container">
    <div class="row min-vh-100 align-items-center justify-content-center">
        <div class="col-sm-10 col-md-6 col-lg-4">
            <div class="card border-0 rounded-4" style="box-shadow:0 12px 35px rgba(51,65,85,.08)">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;background:#598eb8">
                            <i class="bi bi-droplet fs-2"></i>
                        </div>
                        <h4 class="fw-bold mb-1" style="color:#38536b">Laundry IMZ</h4>
                        <p class="text-secondary mb-0">Masuk sebagai Admin atau Kasir</p>
                    </div>
                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" name="username" value="{{ old('username') }}" class="form-control form-control-lg" placeholder="Masukkan username" required autofocus>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Masukkan password" required>
                        </div>
                        <button class="btn btn-primary btn-lg w-100 fw-semibold" type="submit">Masuk</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
