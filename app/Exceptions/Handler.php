<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Throwable;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
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

    /**
     * @param $request
     * @param Throwable $e
     * @return JsonResponse|RedirectResponse|Response
     * @throws Throwable
     */
    public function render($request, Throwable $e): JsonResponse|RedirectResponse|Response
    {
        Log::error($e);

        if($request->expectsJson()){
            if($e instanceof ValidationException){
                $status_code = Response::HTTP_UNPROCESSABLE_ENTITY;
                return $this->sendValidationError($e->errors(), $status_code);
            }

            if($e instanceof NotFoundUserException){
                $status_code = Response::HTTP_NOT_FOUND;
                return $this->sendError($e->getMessage(), $status_code);
            }

            if($e instanceof UnauthorizedUserException){
                $status_code = Response::HTTP_UNAUTHORIZED;
                return $this->sendError($e->getMessage(), $status_code);
            }

        }

        return parent::render($request, $e);
    }

}
