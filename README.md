# Fintehst Project

Fintehst is a PHP command-line application that provides functionality for parsing and processing financial transactions from a file. It includes classes for parsing transactions, validating transactions against predefined rules, and calculating commissions for transactions.

## Installation
To install and use the Fintehst project, follow these steps:

Clone the project from GitHub repository:
``` bash
git clone https://github.com/galcvua/fintehst.git
```
Change directory to the cloned project:
``` bash
cd fintehst
```
Install the project dependencies using Composer:
```
composer install
```

Create a .env file in the root of the project directory if it doesn't already exist.

Obtain an API key from https://apilayer.com/marketplace/exchangerates_data-api.

Add the following line to your .env file, replacing <YOUR_API_KEY> with your actual API key:

```
EXCHANGERATES_APIKEY=<YOUR_API_KEY>
```
## Usage
To run the Fintehst application, use the following command:

```
php app.php input.txt
```
where input.txt is the name of the file containing the transactions you want to process.

## Features
Parse transactions from a file
Validate transactions against predefined rules
Calculate commissions for transactions
Integration with external APIs for BIN lookup and currency exchange rates
Error handling with exception handling and logging
## Requirements
PHP 7.4 or higher
Composer for dependency management
## License
Fintehst is open-source software released under the Candidate Assessment License.

## Author
Fintehst is developed and maintained by Wowa.