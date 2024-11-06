<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\ModelException;
use Illuminate\Validation\AuthenticationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use App\Traits\RequestResponse;
use Illuminate\Database\QueryException;
use Throwable;
class Handler extends ExceptionHandler
{
    use RequestResponse;
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if($exception instanceof ConvertValidationException){
                return $this->ConvertValidationException($exception,$request);
        }
        if($exception instanceof ModelNotFoundException){
            $modulename=strtolower(class_basename($exception->getModle));
            return $this->ErrorResponse('Dose not Exist any {$modulename} with the specified identifier',404);
        }
        if($exception instanceof AuthenticationException){
            return $this->unauthenticated($request,$exception);
        } 
        if($exception instanceof AuthorizationException){
            return $this->ErrorResponse($exception->getMessage(),403);
        }
        if($exception instanceof NotFoundHttpException){
            return $this->ErrorResponse('The Specified Url Cannot Be found',404);
        }
        if($exception instanceof MethodNotAllowedHttpException){
            return $this->ErrorResponse('This Specified Method For the Request is invalid',405);
        }
        if($exception instanceof HttpException){
            return $this->ErrorResponse($exception->getMessage(),$exception->getStatusCode());
        }
        // if($exception instanceof QueryException){
        //     $errorCode=$exception->code;
        //     if($errorCode=='42S22'){
        //         return $this->ErrorResponse('this is error',)
        //     }
        // }
        if(config('app.debug')){
            return parent::render($request, $exception);
        }
        return $this->ErrorResponse('Unexpected Exception. Try later',500);
    }
    protected function ConvertValidationException(ValidationException $e,$request){
        $error=$e->validator->errors()->getMessage();
        return $this->ErrorResponse($error,422);
    }
    protected function unauthenticated($request,AuthenticationException $e){
        return $this->ErrorResponse('unauthenticated',401);
    }
}
