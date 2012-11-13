<?php
namespace PEAR2\Services\Pingback2;

class Server_Callback_FetchSource implements Server_Callback_ISource
{
    /**
     * Fetch the source URL and return it
     *
     * @param string $url URL to fetch
     *
     * @return \HTTP_Request2_Response Response object
     */
    public function fetchSource($url)
    {

        $req = new \HTTP_Request2($url);
        $req->setHeader(
            'accept',
            'text/html;q=0.9'
            . ', application/xhtml+xml;q=0.9'
            . ', */*;q=0.1'
        );
        /* FIXME: add content range to respect:
          In order to avoid susceptibility to denial of service attacks,
          pingback servers that fetch the specified source document
          (as described in section 3) are urged to impose limits on the
          size of the source document to be examined and the rate of data
          transfer.  */
        return $req->send();
    }
}
?>
