<?php

namespace App\Exceptions;

use Exception;

class QueryException extends Exception
{
    protected $erroCode;
    protected $query;
    protected $sqlState;
    protected $driverCode;
    protected $message;

    /**
     * Create a new authentication exception.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($erroCode = 0, $query = "", $sqlState = 0, $driverCode = 0, $message = "")
    {
        $this->erroCode = $erroCode;
        $this->query = $query;
        $this->sqlState = $sqlState;
        $this->driverCode = $driverCode;
        $this->message = $message;

        parent::__construct($message);
    }

    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        \Log::debug("Query: {$this->query}, SQLSTATE: {$this->sqlState}, Driver code: {$this->driverCode}, Error message: {$this->message}");
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->view('errors.queryexception', ['erroCode' => $this->erroCode, 'query' => $this->query, 'sqlState' => $this->sqlState, 'driverCode' => $this->driverCode, 'message' => $this->message], 500);
    }
}
