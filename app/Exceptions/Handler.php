<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        TokenMismatchException::class,
        ModelNotFoundException::class,
        NotFoundHttpException::class,
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
     * @param Exception $exception
     * @return mixed
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        if ($this->shouldReport($exception)) {
            $this->notifyDevTeam($exception);
        }

        parent::report($exception);
    }

    /**
     * Sends an email to the developer about the exception.
     *
     * @param Exception $exception
     * @return mixed
     */
    protected function notifyDevTeam(Exception $exception)
    {
        $ignoredKeywords = [
            'word1',
        ];

        if (app()->environment(['production', 'live'])) {
            try {
                $sendEmail = true;

                $e = FlattenException::create($exception);
                $handler = new SymfonyExceptionHandler();
                $html = $handler->getHtml($e);
                $message = $e->getMessage();

                foreach ($ignoredKeywords as $keyword) {
                    if (stripos($message, $keyword) !== false) {
                        $sendEmail = false;
                    }
                }

                if ($sendEmail) {
                    Mail::send([], [], static function ($message) use ($html) {
                        $message->to(['dev@email.com', 'dev2@email.com'])
                            ->subject(config('app.name') . ' - ' . 'Error Alert')
                            ->setBody($html, 'text/html');
                    });
                }

            } catch (Exception $ex) {
            }
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception $exception
     * @return Response|string
     */
    public function render($request, Exception $exception)
    {
        // redirect user back in case of token mismatch error
        if ($exception instanceof TokenMismatchException) {

            if ($request->ajax() || $request->wantsJson()) {
                return 'Sorry, your session seems to have expired.';
            }

            return redirect()
                ->back()
                ->withErrors(['error' => 'Sorry, your session seems to have expired. Please try again.'])
                ->withInput($request->except('password', 'password_confirmation', '_token'));
        }

        if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
            return response()->json([
                'data' => 'Resource not found'
            ], 404);
        }

        if ($exception instanceof NotFoundHttpException && $request->wantsJson()) {
            return response()->json([
                'data' => 'Resource not found'
            ], 404);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 500);
        }

        return parent::render($request, $exception);
    }
}
