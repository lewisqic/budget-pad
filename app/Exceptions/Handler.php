<?php

namespace App\Exceptions;

use Facades\App\Services\UserService;
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
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
    public function report(Exception $exception)
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
    public function render($request, Exception $e)
    {
        // share our user details with views if it's set
        UserService::shareUserData(\Auth::user());
        $code = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 403;
        $layout = in_array($request->segment(1), ['admin', 'account']) ? $request->segment(1) : 'index';
        \View::share('error_layout', $layout);
        $errors = null;
        if ( method_exists($e, 'getErrors') && $e instanceof \Watson\Validating\ValidationException ) {
            $errors = $e->getErrors();
        }
        $message = !empty($e->getMessage()) ? $e->getMessage() : 'An unknown error has occurred.';
        if ( empty($e->getMessage()) && is_a($e, 'Illuminate\Session\TokenMismatchException') ) {
            $message = 'Your form has expired, please refresh the page and try again.';
        }
        if ( is_a($e, 'Illuminate\Validation\ValidationException') ) {
            $error_messages = [];
            foreach ( $e->errors() as $key => $error_bag ) {
                foreach ( $error_bag as $error ) {
                    $error_messages[] = $error;
                }
            }
            $message = implode(', ', $error_messages);
        }
        if ( $request->ajax() || $request->wantsJson() ) {
            $data = ['success' => false, 'message' => $message, 'file' => $e->getFile(), 'line' => $e->getLine()];
            if ( !is_null($errors) ) {
                $data['errors'] = $errors->all();
                $data['message'] = implode(', ', $errors->all());
            }
            return response()->json($data, $code);
        } else {
            if ( !$request->isMethod('GET') ) {
                if ( !is_null($errors) ) {
                    $message = implode(', ', $errors->all());
                }
                \Msg::danger($message);
                return back()->withInput();
            } else {
                if ( $e instanceof \App\Exceptions\AppException ) {
                    abort(403, $message);
                } else {
                    return parent::render($request, $e);
                }
            }
        }
    }
}
