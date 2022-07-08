<?php

namespace AmarK\LaravelSearchEngine;

use Exception;

class LaravelSearchEngine
{

    /**
     * Custom search engine ID
     *
     * @var string
     */
    protected $engineId;

    /**
     * Google console API key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Original response converted to array
     *
     * @var \stdClass
     */
    protected $originalResponse;

    /**
     * Constructor
     *
     * LaravelSearchEngine constructor.
     * @param $engineId
     * @param $apiKey
     */
    public function __construct()
    {
        $this->engineId = config('laravelSearchEngine.engineId');
        $this->apiKey = config('laravelSearchEngine.apiKey');
    }

    /**
     * Setter for engineId, overrides the value from config
     *
     * @param $engineId
     */
    public function setEngineId($engineId){
        $this->engineId = $engineId;
    }

    /**
     * Setter for apiKey, overrides the value from config
     *
     * @param $engineId
     */
    public function setApiKey($apiKey){
        $this->apiKey = $apiKey;
    }

     public function getResults($phrase, $parameters = array())
    {

        /**
         * Check required parameters
         */
        if ($phrase == '') {
            return array();
        }

        if ($this->engineId == '') {
            throw new \Exception('You must specify a engineId');
        }

        if ($this->apiKey == '') {
            throw new \Exception('You must specify a apiKey');
        }

        /**
         * Create search aray
         */
        $searchArray = http_build_query(array_merge(
            ['key' => $this->apiKey],
            ['q' => $phrase],
            $parameters
        ));

        /**
         * Add unencoded search engine id
         */
        $searchArray = '?cx=' . $this->engineId . '&' . $searchArray;

        /**
         * Prepare CUrl and get result
         */
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/customsearch/v1" . $searchArray);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 0);

        $output = curl_exec($ch);

        $info = curl_getinfo($ch);

        curl_close($ch);

        /**
         * Check HTTP code of the result
         */
        if ($output === false || $info['http_code'] != 200) {

            throw new \Exception("No data returned, code [". $info['http_code']. "] - " . curl_error($ch));
        }

        /**
         * Convert JSON format to object and save
         */
        $this->originalResponse = json_decode($output);

        /**
         * If there are some results, return them, otherwise return blank array
         */
        if(isset($this->originalResponse->items)){
            return $this->originalResponse->items;
        }
        else{
            return array();
        }

    }

    /*
     * @return \stdClass
     * @url https://developers.google.com/custom-search/json-api/v1/reference/cse/list#response
     */
    public function getRawResult(){
        return $this->originalResponse;
    }

    /**
     * Get search information
     *
     * Gets basic information about search
     * [searchTime] - time costs of the search
     * [formattedSearchTime] - time costs of the search formatted according to locale style
     * [totalResults] - number of results
     * [formattedTotalResults] - number of results formatted according to locale style
     *
     * @return \stdClass
     */
    public function getSearchInformation(){
        return $this->originalResponse->searchInformation;
    }

    /**
     * Get the total number of pages where the search phrase is located
     *
     * @return integer
     */
    public function getTotalNumberOfpages(){
        return $this->originalResponse->searchInformation->totalResults;
    }

     /**
     * Get the few details as requested
     *
     * @return array
     */

    public function getFewDetails(){
        if(isset($this->originalResponse->items)){
            $fewResult = array();
            $count = 0;
            foreach($this->originalResponse->items as $item)
            {
                $fewResult[$count]['title'] = $item->htmlTitle; 
                $fewResult[$count]['url'] = $item->link; 
                $fewResult[$count]['description'] = $item->htmlSnippet; 
                $count++;
            }
            return $fewResult;
        }
        else
            return array();
    }

}
