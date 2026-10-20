@if (session('success'))
    <div class="site-alert site-alert--success">
        <span class="fa fa-check-circle"></span>
        <p>{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div class="site-alert site-alert--error">
        <span class="fa fa-exclamation-circle"></span>
        <p>{{ session('error') }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="site-alert site-alert--error">
        <span class="fa fa-exclamation-triangle"></span>
        <div>
            <p>Merci de corriger les points suivants :</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
