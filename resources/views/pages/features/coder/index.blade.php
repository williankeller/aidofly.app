@extends('layouts.app')

@section('content')
    <section class="d-flex flex-column gap-3">
        <a href="/" class="d-inline-flex align-items-center text-sm text-muted text-sm">
            <i class="ti ti-square-rounded-arrow-left-filled"></i>
            <small class="ms-1">Dashboard</small>
        </a>
        <div class="">
            <h2 class="h4">Coding Assistant</h2>
            <p class="text-muted">Generate high quality code in seconds.</p>
        </div>
    </section>

    <section class="mt-4 p-5 card" data-bs-toggle="collapse">
        <h3 class="h5">Prompts</h3>
        <form class="d-grid gap-3 mt-3">
            <div>
                <label for="prompt">
                    Description
                    <i class="bi bi-question-square-fill text-muted"></i>
                </label>
                <textarea class="form-control mt-2" id="prompt" name="prompt" placeholder="Describe request" rows="3"
                    required></textarea>
            </div>
            <div>
                <label for="language">Programming language</label>
                <input type="text" class="form-control mt-2" id="language" name="language"
                    placeholder="Python, JavaScript etc." required>
            </div>
            <div>
                <button type="submit" class="btn btn-primary w-100">
                    Generate result
                </button>
            </div>
        </form>
    </section>
@endsection
