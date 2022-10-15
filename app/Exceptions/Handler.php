<?php

namespace App\Exceptions;
use Throwable;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {

            if ( strpos( $request->headers->get('accept'), "application/json" ) >= 0 ) {
                $response = new \Illuminate\Http\Response();
                $response->header("Content-Type",  "application/json");
                print json_encode( array('error' => 'You are not authorized to perform that function - ' . $exception->getMessage() ));
                return $response;
            }

            session()->flash('msg', 'You are not authorized to perform that function - ' . $exception->getMessage() );
            return back()->withInput();

        }
        return parent::render($request, $exception);
    }
}
