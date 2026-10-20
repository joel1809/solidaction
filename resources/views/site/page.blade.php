@extends('layouts.site')

@section('title', $page->title)
@section('meta_description', $page->meta_description)

@section('content')

    <x-site.breadcrumb :title="$page->title" :items="[$page->title]" :image="$page->image" />

    <section class="static-page-content">
        <div class="container">
            <div class="row">
                <div class="col-xl-10 offset-xl-1">
                    <div class="content-prose">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
