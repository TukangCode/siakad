<?php

namespace Stmik\Providers;

use Illuminate\Support\ServiceProvider;
use Panatau\Tools\PanatauToolsServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // lakukan registrasi custom validator
        $path = \Stmik\Http\Validators\IsiFRSValidator::class . '@kelasBisaDiambil';
        \Validator::extend('kelas_bisa_diambil', $path, 'Kelas tidak bisa diambil');
        $path =\Stmik\Http\Validators\DosenKelasMKValidator::class . '@dosenBolehAjarKelasMk';
        \Validator::extend('dosen_boleh_ajar_kelas_mk', $path, 'Kelas tidak bisa diajar dosen ybs');

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->environment()=='local')
        {
            $this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
//            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
        $this->app->register(PanatauToolsServiceProvider::class);
    }
}
