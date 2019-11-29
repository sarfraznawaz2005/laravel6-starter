<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OptimizeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // do nothing in case of console
        if (app()->runningInConsole()) {
            return $response;
        }

        // do nothing in case of ajax request
        if ($request->ajax() || $request->expectsJson()) {
            return $response;
        }

        // do nothing in case of method which is not GET
        if (!$request->isMethod('get')) {
            return $response;
        }

        // do nothing in case of binary content
        if ($response instanceof BinaryFileResponse) {
            return $response;
        }

        $buffer = $response->getContent();

        ini_set('pcre.recursion_limit', '16777');

        // enable GZip, too!
        ini_set('zlib.output_compression', 'On');

        $regEx = '%# Collapse whitespace everywhere but in blacklisted elements.
        (?>             # Match all whitespans other than single space.
          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
          [^<]*+        # Either zero or more non-"<" {normal*}
          (?:           # Begin {(special normal*)*} construct
            <           # or a < starting a non-blacklist tag.
            (?!/?(?:textarea|pre|script)\b)
            [^<]*+      # more non-"<" {normal*}
          )*+           # Finish "unrolling-the-loop"
          (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre|script)\b
          | \z          # or end of file.
          )             # End alternation group.
        )  # If we made it here, we are not in a blacklist tag.
        %Six';

        $newBuffer = preg_replace($regEx, ' ', $buffer);

        if ($newBuffer !== null) {
            $buffer = $newBuffer;
        }

        $response->setContent($buffer);

        return $response;
    }
}
