@props(['causes' => null])

@php
    $causes ??= \App\Models\Cause::published()->orderBy('title')->get(['id', 'title']);
@endphp

<section class="donate-form-area">
    <div class="donate-form-area_bg" style="background-image: url({{ theme_asset('assets/images/pattern/thm-pattern-3.png') }});"></div>
    <div class="auto-container">
        <div class="row">
            <div class="col-xl-12">
                <div class="donate-form_box">
                    <div class="title">
                        <h2>Don rapide</h2>
                        <p>Chaque geste compte, même le plus petit</p>
                    </div>
                    <div class="donate-form">
                        <form method="get" action="{{ route('donations.create') }}" class="donate-form-1">
                            <ul class="clearfix">
                                <li class="select-box left">
                                    <select class="selectpicker" name="frequence">
                                        <option value="one_time">Don unique</option>
                                        <option value="monthly">Don mensuel</option>
                                        <option value="yearly">Don annuel</option>
                                    </select>
                                </li>
                                <li class="select-box">
                                    <select class="selectpicker" name="montant">
                                        @foreach (explode(',', setting('donation_amounts', '2000,5000,10000,25000,50000')) as $preset)
                                            <option value="{{ trim($preset) }}">{{ money(trim($preset)) }}</option>
                                        @endforeach
                                    </select>
                                </li>
                                <li class="select-box right">
                                    <select class="selectpicker" name="cause">
                                        <option value="">Toutes nos actions</option>
                                        @foreach ($causes as $cause)
                                            <option value="{{ $cause->id }}">{{ Str::limit($cause->title, 34) }}</option>
                                        @endforeach
                                    </select>
                                </li>
                            </ul>

                            <div class="button-box">
                                <button class="btn-one btn-one-style2" type="submit">
                                    <span class="txt"><i class="arrow1 fa fa-check-circle"></i>Je donne</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
