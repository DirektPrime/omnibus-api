<?php


namespace Rostro\Omnibus;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use RuntimeException;

class Client
{
    const HOST = 'https://omnibus.phoenixportals.com';

    protected string $host;

    protected object $accessToken;

    protected GuzzleClient $guzzle;

    public function __construct(array $options)
    {
        $this->host = $options['host'] ?? self::HOST;
        $this->guzzle = new GuzzleClient();
        $this->login($options);
    }

    private function login(array $options): void
    {
        $url = $this->getUrl('login');

        $options = [
            'form_params' => [
                'email' => $options['username'],
                'password' => $options['password'],
            ],
        ];

        $response = $this->guzzle->request('POST', $url, $options);
        $response = json_decode($response->getBody());

        if ($response->result == 'error') {
            $error = $response->errors[0] ?? 'Unknown error';
            throw new RuntimeException($error);
        }

        $this->accessToken = $response;
    }

    private function apiRequest(string $api, ?array $parameters = []): array|object
    {
        $headers = $this->headers();
        $url = $this->getUrl($api);
        $request = new GuzzleRequest('GET', $url, $headers);
        $options = ['query' => $parameters];

        $response = $this->guzzle->send($request, $options);

        if ($response->getStatusCode() !== 200) {
            print_r(json_decode($response->getBody()));
            throw new RuntimeException('API request failed');
        }

        return json_decode($response->getBody());
    }

    private function headers(): array
    {
        $accept = ['Accept' => 'application/json'];

        if ($this->accessToken->result == 'success') {
            return ['Authorization' => 'Bearer ' . $this->accessToken->token] + $accept;
        }

        return $accept;
    }

    private function getUrl($path): string
    {
        return $this->host . '/api/' . $path;
    }

    /**
     * Get information about your user account.
     *
     * @return array|object
     */
    public function me()
    {
        return $this->apiRequest('user');
    }

    /**
     * Get Iress commissions data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     *     <dt>search</dt>
     *     <dd>A search string to match against instruments and exchanges.</dd>
     * </dl>
     * @return array|object
     */
    public function iressCommissions($params = [])
    {
        return $this->apiRequest('iress/commissions', $params);
    }

    /**
     * Get Iress dividends data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     *     <dt>search</dt>
     *     <dd>A search string to match against instruments and exchanges.</dd>
     * </dl>
     * @return array|object
     */
    public function iressDividends($params = [])
    {
        return $this->apiRequest('iress/dividends', $params);
    }

    /**
     * Get Iress dividend tax data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     *     <dt>search</dt>
     *     <dd>A search string to match against instruments and exchanges.</dd>
     * </dl>
     * @return array|object
     */
    public function iressDividendTax($params = [])
    {
        return $this->apiRequest('ress/dividendtax', $params);
    }

    /**
     * Get Iress financing data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     *     <dt>search</dt>
     *     <dd>A search string to match against instruments and exchanges.</dd>
     * </dl>
     * @return array|object
     */
    public function iressFinancing($params = [])
    {
        return $this->apiRequest('iress/financing', $params);
    }

    /**
     * Get Iress Greek tax data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     *     <dt>search</dt>
     *     <dd>A search string to match against instruments and exchanges.</dd>
     * </dl>
     * @return array|object
     */
    public function iressPGreekTax($params = [])
    {
        return $this->apiRequest('iress/greektax', $params);
    }

    /**
     * Get Iress interest data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     * </dl>
     * @return array|object
     */
    public function iressInterest($params = [])
    {
        return $this->apiRequest('iress/interest', $params);
    }

    /**
     * Get Iress Italian tax data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     *     <dt>search</dt>
     *     <dd>A search string to match against instruments and exchanges.</dd>
     * </dl>
     * @return array|object
     */
    public function iressItalianTax($params = [])
    {
        return $this->apiRequest('iress/italiantax', $params);
    }

    /**
     * Get Iress short borrowing data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     *     <dt>search</dt>
     *     <dd>A search string to match against instruments and exchanges.</dd>
     * </dl>
     * @return array|object
     */
    public function iressShortBorrowing($params = [])
    {
        return $this->apiRequest('iress/shortborrowing', $params);
    }

    /**
     * Get Iress money flow data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     * </dl>
     * @return array|object
     */
    public function iressMoneyFlow($params = [])
    {
        return $this->apiRequest('iress/moneyflow', $params);
    }

    /**
     * Get Iress money flow totals data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from (required)</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to (required)</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     * </dl>
     * @return array|object
     */
    public function iressMoneyFlowTotals($params = [])
    {
        return $this->apiRequest('iress/moneyflow/totals', $params);
    }

    /**
     * Get Iress money flow net data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     * </dl>
     * @return array|object
     */
    public function iressMoneyFlowNet($params = [])
    {
        return $this->apiRequest('iress/moneyflow/net', $params);
    }

    /**
     * Get Iress money flow net totals data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from (required)</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to (required)</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     * </dl>
     * @return array|object
     */
    public function iressMoneyFlowNetTotals($params = [])
    {
        return $this->apiRequest('iress/moneyflow/net/totals', $params);
    }

    /**
     * Get Iress transactions data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     *     <dt>search</dt>
     *     <dd>A search string to match against instruments and exchanges.</dd>
     * </dl>
     * @return array|object
     */
    public function iressTransactions($params = [])
    {
        return $this->apiRequest('iress/transactions', $params);
    }

    /**
     * Get Iress account balances data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>date (required)</dt>
     *     <dd>The balance date.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     * </dl>
     * @return array|object
     */
    public function iressAccountBalances($params = [])
    {
        return $this->apiRequest('iress/accountbalances', $params);
    }

    /**
     * Get Iress average positions data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>date (required)</dt>
     *     <dd>Include positions open on this date.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     *     <dt>search</dt>
     *     <dd>A search string to match against instruments and exchanges.</dd>
     * </dl>
     * @return array|object
     */
    public function iressAvgPositions($params = [])
    {
        return $this->apiRequest('iress/avgpositions', $params);
    }

    /**
     * Get Iress net balances data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>date (required)</dt>
     *     <dd>The balance date.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     * </dl>
     * @return array|object
     */
    public function iressNetBalances($params = [])
    {
        return $this->apiRequest('iress/netbalances', $params);
    }

    /**
     * Get Iress positions data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>date (required)</dt>
     *     <dd>Include positions open on this date.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     *     <dt>search</dt>
     *     <dd>A search string to match against instruments and exchanges.</dd>
     * </dl>
     * @return array|object
     */
    public function iressPositions($params = [])
    {
        return $this->apiRequest('iress/positions', $params);
    }

    /**
     * Get Iress trades data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     *     <dt>search</dt>
     *     <dd>A search string to match against instruments and exchanges.</dd>
     *     <dt>destinations</dt>
     *     <dd>The destinations to include (array or comma separated string).</dd>
     *     <dt>counterparties</dt>
     *     <dd>The counterparties to include (array or comma separated string).</dd>
     * </dl>
     * @return array|object
     */
    public function iressTrades($params = [])
    {
        return $this->apiRequest('iress/trades', $params);
    }

    /**
     * Get Devex commissions data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>instrument</dt>
     *     <dd>The instruments to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     * </dl>
     * @return array|object
     */
    public function devexCommissions($params = [])
    {
        return $this->apiRequest('devex/commissions', $params);
    }

    /**
     * Get Devex financing data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>instrument</dt>
     *     <dd>The instruments to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     * </dl>
     * @return array|object
     */
    public function devexFinancing($params = [])
    {
        return $this->apiRequest('devex/financing', $params);
    }

    /**
     * Get Devex interest data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     * </dl>
     * @return array|object
     */
    public function devexInterest($params = [])
    {
        return $this->apiRequest('devex/interest', $params);
    }

    /**
     * Get Devex Italian tax data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>instrument</dt>
     *     <dd>The instruments to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     * </dl>
     * @return array|object
     */
    public function devexItalianTax($params = [])
    {
        return $this->apiRequest('devex/italiantax', $params);
    }

    /**
     * Get Devex short borrowing data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>instrument</dt>
     *     <dd>The instruments to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     * </dl>
     * @return array|object
     */
    public function devexShortBorrowing($params = [])
    {
        return $this->apiRequest('devex/shortborrowing', $params);
    }

    /**
     * Get Devex money flow data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     *     <dt>showZeroValued</dt>
     *     <dd>Pass in 1 to include zero valued records or 0 (default) to exclude them.</dd>
     * </dl>
     * @return array|object
     */
    public function devexMoneyFlow($params = [])
    {
        return $this->apiRequest('devex/moneyflow', $params);
    }

    /**
     * Get Devex money flow totals data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from (required)</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to (required)</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     * </dl>
     * @return array|object
     */
    public function devexMoneyFlowTotals($params = [])
    {
        return $this->apiRequest('devex/moneyflow/totals', $params);
    }

    /**
     * Get Devex transactions data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     *     <dt>showZeroValued</dt>
     *     <dd>Pass in 1 to include zero valued records or 0 (default) to exclude them.</dd>
     * </dl>
     * @return array|object
     */
    public function devexTransactions($params = [])
    {
        return $this->apiRequest('devex/transactions', $params);
    }

    /**
     * Get Devex average positions data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>date (required)</dt>
     *     <dd>Include positions open on this date.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>instrument</dt>
     *     <dd>The instruments to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     * </dl>
     * @return array|object
     */
    public function devexAvgPositions($params = [])
    {
        return $this->apiRequest('devex/avgpositions', $params);
    }

    /**
     * Get Devex closed positions data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>instrument</dt>
     *     <dd>The instruments to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     * </dl>
     * @return array|object
     */
    public function devexClosedPositions($params = [])
    {
        return $this->apiRequest('devex/closedpositions', $params);
    }

    /**
     * Get Devex positions data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>date (required)</dt>
     *     <dd>Include positions open on this date.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>instrument</dt>
     *     <dd>The instruments to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     * </dl>
     * @return array|object
     */
    public function devexPositions($params = [])
    {
        return $this->apiRequest('devex/positions', $params);
    }

    /**
     * Get Devex trades data.
     *
     * @param $params array An array of API request parameters.
     * <dl>
     *     <dt>from</dt>
     *     <dd>The start of the date range.</dd>
     *     <dt>to</dt>
     *     <dd>The end of the date range.</dd>
     *     <dt>currency</dt>
     *     <dd>The currencies to include (array or comma separated string).</dd>
     *     <dt>instrument</dt>
     *     <dd>The instruments to include (array or comma separated string).</dd>
     *     <dt>accounts</dt>
     *     <dd>The accounts to include (array or comma separated string).</dd>
     *     <dt>clients</dt>
     *     <dd>The clients to include (array or comma separated string).</dd>
     * </dl>
     * @return array|object
     */
    public function devexTrades($params = [])
    {
        return $this->apiRequest('devex/trades', $params);
    }
}
