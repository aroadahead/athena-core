<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Controller;

use Laminas\Mvc\Controller\AbstractActionController;

/**
 * Abstract Mvc controller
 */
abstract class AbstractMvcController extends AbstractActionController
{
    // Informational 1xx
    public const HTTP_CONTINUE = 100;
    public const SWITCH_PROTOCOLS = 101;
    // Successful 2xx
    public const OK = 200;
    public const CREATED = 201;
    public const ACCEPTED = 202;
    public const NONAUTHORITATIVE = 203;
    public const NO_CONTENT = 204;
    public const RESET_CONTENT = 205;
    public const PARTIAL_CONTENT = 206;
    // Redirection 3xx
    public const MULTIPLE_CHOICES = 300;
    public const MOVED_PERMANENTLY = 301;
    public const FOUND = 302;
    public const SEE_OTHER = 303;
    public const NOT_MODIFIED = 304;
    public const USE_PROXY = 305;
    // 306 is deprecated but reserved
    public const TEMP_REDIRECT = 307;
    // Client Error 4xx
    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const PAYMENT_REQUIRED = 402;
    public const FORBIDDEN = 403;
    public const NOT_FOUND = 404;
    public const NOT_ALLOWED = 405;
    public const NOT_ACCEPTABLE = 406;
    public const PROXY_AUTH_REQUIRED = 407;
    public const REQUEST_TIMEOUT = 408;
    public const CONFLICT = 409;
    public const GONE = 410;
    public const LENGTH_REQUIRED = 411;
    public const PRECONDITION_FAILED = 412;
    public const LARGE_REQUEST_ENTITY = 413;
    public const LONG_REQUEST_URI = 414;
    public const UNSUPPORTED_TYPE = 415;
    public const UNSATISFIABLE_RANGE = 416;
    public const EXPECTATION_FAILED = 417;
    // Server Error 5xx
    public const SERVER_ERROR = 500;
    public const NOT_IMPLEMENTED = 501;
    public const BAD_GATEWAY = 502;
    public const UNAVAILABLE = 503;
    public const GATEWAY_TIMEOUT = 504;
    public const UNSUPPORTED_VERSION = 505;
    public const BANDWIDTH_EXCEEDED = 509;
}
