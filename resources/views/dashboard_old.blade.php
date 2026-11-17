<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiny Guide Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary px-4">
    <span class="navbar-brand mb-0 h1">Tiny Guide Dashboard</span>
</nav>

<div class="container py-4">
    <div class="card shadow-sm p-4 mt-4">
    <h4 class="mb-3">Add Glucose Record</h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/dashboard" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Child Name</label>
            <input
                type="text"
                name="child_name"
                value="{{ old('child_name') }}"
                class="form-control"
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Glucose Level</label>
            <input
                type="number"
                name="glucose"
                value="{{ old('glucose') }}"
                class="form-control"
            >
        </div>

        <button type="submit" class="btn btn-primary">
            Save
        </button>
    </form>
</div>

    <h2 class="mb-4">Welcome to Tiny Guide</h2>

    <div class="row g-3">

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card shadow-sm p-3">
                <h5>Children</h5>
                <p class="fs-3 mb-0">0</p>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card shadow-sm p-3">
                <h5>Glucose Records</h5>
                <p class="fs-3 mb-0">0</p>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card shadow-sm p-3">
                <h5>Meals</h5>
                <p class="fs-3 mb-0">0</p>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card shadow-sm p-3">
                <h5>Reports</h5>
                <p class="fs-3 mb-0">0</p>
            </div>
        </div>

    </div>

</div>

</body>
</html>
