<?php

use App\Models\Slider;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('sliders:optimize-images', function () {
    Slider::whereNotNull('image')->each(function (Slider $slider) {
        $slider->refreshImageVariants();
        $this->line("Diapositive {$slider->id} : ".count($slider->image_variants ?? []).' déclinaison(s) WebP.');
    });
})->purpose('Génère les déclinaisons WebP des images du carrousel');
