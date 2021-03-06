<?php


namespace miralsoft\docbee\api;


use ReflectionClass;

abstract class DocbeeAPICall
{
    /** @var string The main function from the call */
    protected string $mainFunction = '';

    /** @var string The sub function from the call */
    protected string $subFunction = '';

    /** @var Config The config for the requests */
    protected Config $config;

    /**
     * WeclappAPICall constructor.
     */
    public function __construct(Config $config)
    {
        $reflect = new ReflectionClass($this);
        $this->mainFunction = lcfirst($reflect->getShortName()) . '/';

        $this->config = $config;
    }

    /**
     * Set the sub function of this call
     * @param string $subFunction The subfunction
     */
    public function setSubFunction(string $subFunction)
    {
        $this->subFunction = $subFunction;
    }

    /**
     * Do the API Call
     *
     * @param array $data The data for this call
     * @param string $requestType The request type of this call
     * @param bool $formateResult should the result be formated
     * @return array The return value of the api call
     */
    protected function call(array $data = array(), string $requestType = RequestType::GET, bool $formateResult = true)
    {
        $result = APICall::call($this->config, $this->mainFunction . $this->subFunction, $data, $requestType);

        if ($formateResult) {
            // If the result is a json encode it
            if (Util::isJson($result)) $result = json_decode($result, true);
            // If no json give a empty array
            else $result = array();

            return count($result) > 0 && isset($result['result']) ? $result['result'] : $result;
        }

        return $result;
    }
}