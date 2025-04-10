<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
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

        if($e instanceof MethodNotAllowedHttpException) {
            return $this->sendError($e->getMessage());
        }

        if($request->expectsJson()){
            if($e instanceof QueryException){
                $status_code =  Response::HTTP_INTERNAL_SERVER_ERROR;
                return $this->sendError('Could not execute query', $status_code);
            }

            if($e instanceof ValidationException){
                $status_code = Response::HTTP_UNPROCESSABLE_ENTITY;
                return $this->sendValidationError($e->errors(), $status_code);
            }

            if($e instanceof NotFoundHttpException){
                $status_code = Response::HTTP_NOT_FOUND;
                return $this->sendError($e->getMessage(), $status_code);
            }

            if($e instanceof UnauthorizedException){
                $status_code = Response::HTTP_UNAUTHORIZED;
                return $this->sendError($e->getMessage(), $status_code);
            }

            if($e instanceof AuthenticationException){
                $status_code = Response::HTTP_UNAUTHORIZED;
                return $this->sendError('Unauthenticated or Token Expired, please try to login again', $status_code);
            }

            if($e instanceof BadRequestException){
                $status_code = Response::HTTP_BAD_REQUEST;
                return $this->sendError($e->getMessage(), $status_code);
            }

            if($e instanceof TooManyRequestsHttpException){
                $status_code = Response::HTTP_TOO_MANY_REQUESTS;
                return $this->sendError('Too many requests, please try later', $status_code);
            }

            if($e instanceof  SameOldPasswordException){
                $status_code = Response::HTTP_FORBIDDEN;
                return $this->sendError('Same old password!, please change it.', $status_code);
            }
        }

        return parent::render($request, $e);
    }

}
