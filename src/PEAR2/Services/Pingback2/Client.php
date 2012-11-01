<?php
namespace PEAR2\Services\Pingback2;

class Client
{
    /**
     * HTTP request object that's used to do the requests
     * @var \HTTP_Request2
     */
    protected $request;


    /**
     * Initializes the HTTP request object
     */
    public function __construct()
    {
        $this->setRequest(new \HTTP_Request2());
    }

    /**
     * Send a pingback, indicating a link from source to target.
     * The target's pingback server will be discovered automatically.
     *
     * @param string $sourceUri URL on this side, it links to $targetUri
     * @param string $targetUri Remote URL that shall be notified about source
     *
     * @return Response_Ping Pingback response object containing all error
     *                       and status information.
     */
    public function send($sourceUri, $targetUri)
    {
        //FIXME: validate $sourceUri, $targetUri

        $serverUri = $this->discoverServer($targetUri);
        if ($serverUri === false) {
            //target resource is not pingback endabled
            return new Response_Ping(
                'No pingback server found for URI',
                Response_Ping::PINGBACK_UNSUPPORTED
            );
        }

        return $this->sendPingback($serverUri, $sourceUri, $targetUri);
    }

    /**
     * Autodiscover the pingback server for the given URI.
     *
     * @param string $targetUri Some URL to discover the pingback server of
     *
     * @return string|boolean False when it failed, server URI on success
     */
    protected function discoverServer($targetUri)
    {
        //at first, try a HEAD request that does not transfer so much data
        $req = $this->getRequest();
        $req->setUrl($targetUri);
        $req->setMethod(\HTTP_Request2::METHOD_HEAD);
        $res = $req->send();

        $headerUri = $res->getHeader('X-Pingback');
        //FIXME: validate URI
        if ($headerUri !== null) {
            return $headerUri;
        }

        //HEAD failed, do a normal GET
        $req->setMethod(\HTTP_Request2::METHOD_GET);
        $res = $req->send();

        //yes, maybe the server does return this header now
        $headerUri = $res->getHeader('X-Pingback');
        //FIXME: validate URI
        if ($headerUri !== null) {
            return $headerUri;
        }

        $body = $res->getBody();
        $regex = '#<link rel="pingback" href="([^"]+)" ?/?>#';
        if (preg_match($regex, $body, $matches) === false) {
            return false;
        }

        $uri = $matches[1];
        $uri = str_replace(
            array('&amp;', '&lt;', '&gt;', '&quot;'),
            array('&', '<', '>', '"'),
            $uri
        );
        //FIXME: validate URI
        return $uri;
    }

    /**
     * Contacts the given pingback server and tells him that source links to
     * target.
     *
     * @param string $serverUri URL of XML-RPC server that implements pingback
     * @param string $sourceUri URL on this side, it links to $targetUri
     * @param string $targetUri Remote URL that shall be notified about source
     *
     * @return Response_Ping Pingback response object containing all error
     *                       and status information.
     */
    protected function sendPingback($serverUri, $sourceUri, $targetUri)
    {
        $encSourceUri = htmlspecialchars($sourceUri);
        $encTargetUri = htmlspecialchars($targetUri);

        $req = $this->getRequest();
        $req->setUrl($serverUri)
            ->setMethod(\HTTP_Request2::METHOD_POST)
            ->setHeader('Content-type: text/xml')
            ->setBody(
<<<XML
<?xml version="1.0" encoding="utf-8"?>
<methodCall>
 <methodName>pingback.ping</methodName>
 <params>
  <param><value><string>$encSourceUri</string></value></param>
  <param><value><string>$encTargetUri</string></value></param>
 </params>
</methodCall>
XML
            );
        $res = $req->send();

        $pres = new Response_Ping();
        $pres->setResponse($res);
        return $pres;
    }

    //FIXME: implement http://old.aquarionics.com/misc/archives/blogite/0198.html

    /**
     * Returns the HTTP request object that's used internally
     *
     * @return \HTTP_Request2
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Sets a custom HTTP request object that will be used to do HTTP requests
     *
     * @param \HTTP_Request2 $request Request object
     *
     * @return self
     */
    public function setRequest(\HTTP_Request2 $request)
    {
        $this->request = $request;
        return $this;
    }

}

?>
