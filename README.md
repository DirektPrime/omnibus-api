# Omnibus API Client Library

## Installation

The recommended way to install this library is through
[Composer](https://getcomposer.org/).

```bash
composer require rostro.com/omnibus-api:dev-main
```

## Usage

```php
use Rostro\Omnibus\Client;

$client = new Client([
    'username' => ‘your account email address’,
    'password' => ‘your account password’,
]);

// Get your user information.
$results = $client->me();

// Get your Iress trades data.
$results = $client->iressTrades([
    ‘from’ => ‘2024-07-01’,
    ‘to’ => ‘2024-08-01’,
]);

// Get your Devex trades data.
$results = $client->devexTrades([
    ‘from’ => ‘2024-07-01’,
    ‘to’ => ‘2024-08-01’,
]);
```

## List of API Functions.

 - `me` - Get your user information.
 - `iressCommissions` - Get Iress commissions data.
 - `iressDividends` - Get Iress dividends data.
 - `iressDividendTax` - Get Iress dividend tax data.
 - `iressFinancing` - Get Iress financing data.
 - `iressPGreekTax` - Get Iress Greek tax data.
 - `iressInterest` - Get Iress interest data.
 - `iressItalianTax` - Get Iress Italian tax data.
 - `iressShortBorrowing` - Get Iress short borrowing data.
 - `iressMoneyFlow` - Get Iress money flow data.
 - `iressMoneyFlowTotals` - Get Iress money flow totals data.
 - `iressMoneyFlowNet` - Get Iress money flow net data.
 - `iressMoneyFlowNetTotals` - Get Iress money flow net totals  data.
 - `iressTransactions` - Get Iress commissions data.
 - `iressAccountBalances` - Get Iress account balances data.
 - `iressAvgPositions` - Get Iress average positions data.
 - `iressNetBalances` - Get Iress net / ios balances  data.
 - `iressPositions` - Get Iress positions data.
 - `iressTrades` - Get Iress trades data.
 - `devexCommissions` - Get the Devex commissions data.
 - `devexFinancing` - Get the Devex financing data.
 - `devexInterest` - Get the Devex interest data.
 - `devexItalianTax` - Get the Devex Italian tax data.
 - `devexShortBorrowing` - Get the Devex short borrowing data.
 - `devexMoneyFlow` - Get the Devex money flow data.
 - `devexMoneyFlowTotals` - Get the Devex money flow totals data.
 - `devexTransactions` - Get the Devex transactions data.
 - `devexAvgPositions` - Get the Devex average positions data.
 - `devexClosedPositions` - Get the Devex closed positions data.
 - `devexPositions` - Get the Devex open positions data.
 - `devexTrades` - Get the Devex trades data.

Details of the API parameters can be found in the API menu of the application.
