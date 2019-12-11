<?php 


class HubDB {

    /**
     * Get pricing table data
     */
    function get_pricing_data($portalId) {
        $endpoint = 'https://api.hubapi.com/hubdb/api/v1/tables';
        $queryString = build_query_string(['portalId' => $portalId]);
        return $this->client->request('get', $endpoint, [], $queryString);
    }


    /**
     * Get Specific Table data 
     * 
     */
    public function tableInfo($portalId, $tableId) {
        $endpoint = 'https://api.hubapi.com/hubdb/api/v1/tables/'.$tableId;
        $queryString = build_query_string(['portalId' => $portalId]);
        return $this->client->request('get', $endpoint, [], $queryString);
    }


    /**
     * Get table rows.
     *
     * @param int   $portalId Hub/Portal ID
     * @param int   $tableId  table ID
     * @param array $params   key-value array to filter and sort rows
     *
     * @return \Psr\Http\Message\ResponseInterface|\SevenShores\Hubspot\Http\Response
     */
    public function rows($portalId, $tableId, array $params) {
        $endpoint = 'https://api.hubapi.com/hubdb/api/v1/tables/'.$tableId.'/rows';
        $queryString = build_query_string(array_merge(['portalId' => $portalId], $params));
        return $this->client->request('get', $endpoint, [], $queryString);
    }



}