<div class="mt-auto m-auto py-3 bg-white" style="max-width: 230px;">
    <form class="d-flex align-items-center text-muted" action="{{ route('locale.switch') }}" method="post">
        <i class="fs-5 ti ti-language me-1"></i>
        <select name="locale" class="form-select form-select-sm text-muted border-0" onchange="this.form.submit()">
            @foreach ($locales as $code => $locale)
                <option value="{{ $code }}" @selected(old('locale', app()->getLocale()) == $code)>{{ $locale }}</option>
            @endforeach
        </select>
        @csrf
    </form>
</div>
