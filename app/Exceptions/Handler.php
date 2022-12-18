<?php

namespace App\Exceptions;

use Throwable;
use Inertia\Inertia;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use App\Exceptions\ModelCannotBeDeletedException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render( $request, Throwable $e )
    {
        /**
         * @var \Illuminate\Http\Response
         */
        $response = parent::render($request, $e);

        if ( $e instanceof ValidationException ) {
            return $response;
        }

        if ( $e instanceof ModelCannotBeDeletedException ) {
            return back()
                ->with('status.error', true)
                ->with('status.message', $e->getMessage());
        }

        return Inertia::render('Error', ['status' => $response->status()])
            ->toResponse($request)
            ->setStatusCode($response->status());
    }     
}
