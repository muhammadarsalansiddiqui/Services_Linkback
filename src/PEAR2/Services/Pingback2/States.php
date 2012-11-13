<?php
namespace PEAR2\Services\Pingback2;

class States
{
    /**
     * Pingback server did not return the correct HTTP status code
     */
    const HTTP_STATUS = 100;

    /**
     * Pingback server did not return the correct HTTP content type
     */
    const CONTENT_TYPE = 101;

    /**
     * Remote URL does not have a pingback server
     */
    const PINGBACK_UNSUPPORTED = 200;

    /**
     * The XML-RPC call had too few parameters and could not be processed.
     * Defined by "Specification for Fault Code Interoperability".
     */
    const PARAMETER_MISSING = -32602;

    /**
     * The XML-RPC method is not supported and cannot be handled.
     * Defined by "Specification for Fault Code Interoperability".
     */
    const METHOD_UNSUPPORTED = -32601;

    /**
     * The source URI could not be retrieved.
     * Defined by the pingback specification.
     */
    const SOURCE_URI_NOT_FOUND = 16;

    /**
     * The source URI content does not contain a link to the target.
     * Defined by the pingback specification.
     */
    const NO_LINK_IN_SOURCE = 17;

    /**
     * The specified target URI does not exist.
     * Defined by the pingback specification.
     */
    const TARGET_URI_NOT_FOUND = 32;

    /**
     * The pingback has already been registered.
     * Defined by the pingback specification.
     */
    const ALREADY_REGISTERED = 48;

    /**
     * Access denied.
     * Defined by the pingback specification.
     */
    const ACCESS_DENIED = 49;

    /**
     * The server could not communicate with an upstream server,
     * or received an error from an upstream server, and therefore
     * could not complete the request.
     * This is similar to HTTP's 402 Bad Gateway error.
     * Defined by the pingback specification.
     */
    const UPSTREAM_ERROR = 50;
}

?>