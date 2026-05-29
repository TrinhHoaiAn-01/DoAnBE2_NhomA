<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ModelNotFoundException $exception, Request $request) {
            if ($request->is('admin/*')) {
                return redirect()
                    ->route('admin.dashboard')
                    ->with('error', 'Bản ghi không còn tồn tại hoặc đã được xóa trước đó. Vui lòng mở lại danh sách mới nhất trước khi thao tác.');
            }

            return null;
        });

        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            if ($request->is('admin/*')) {
                return redirect()
                    ->route('admin.dashboard')
                    ->with('error', 'Bản ghi không còn tồn tại hoặc đã được xóa trước đó. Vui lòng mở lại danh sách mới nhất trước khi thao tác.');
            }

            return null;
        });

        $exceptions->render(function (HttpException $exception, Request $request) {
            if ($exception->getStatusCode() === 403 && $request->expectsJson() === false) {
                return redirect()
                    ->route('home')
                    ->with('error', 'Bạn không có quyền thực hiện thao tác này. Hệ thống đã chặn yêu cầu để bảo vệ dữ liệu.');
            }

            return null;
        });
    })

    ->create();
