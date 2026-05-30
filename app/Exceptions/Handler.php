<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        "current_password",
        "password",
        "password_confirmation",
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    // public function render($request, Throwable $exception)
    // {
    //     // Kalau Administrator, tampilkan error detail (default Laravel bawaan)
    //     if (session('nama_level_user') === 'Administrator') {
    //         return parent::render($request, $exception);
    //     }

    //     // Kalau bukan Administrator, tampilkan error custom
    //     $statusCode = 500; // default

    //     // cek apakah exception turunan HttpExceptionInterface
    //     if ($exception instanceof HttpExceptionInterface) {
    //         $statusCode = $exception->getStatusCode();
    //     }

    //     // cek apakah ada view khusus sesuai kode error (misal errors/404.blade.php)
    //     if (view()->exists("components.errors.{$statusCode}")) {
    //         return response()->view("components.errors.{$statusCode}", [
    //             'code' => $statusCode
    //         ], $statusCode);
    //     }

    //     // fallback kalau view spesifik tidak ada
    //     return response()->view("components.errors.custom", [
    //         'code' => $statusCode
    //     ], $statusCode);
    // }
}
