# AWGment
A REST API written in MySQL / PHP to manage users, user groups, permissions and more.

## Dependencies / Prerequisites
- PHP 5
- MySQL Database

## Installation

1. Copy all of the files for AWGment into a folder of your choice on your hosting platform.
1. Create the file "db/credentials.json" and set it's contents:
```json
  {
    "db_name": "YOUR DATABASE NAME",
    "host": "localhost",
    "username": "YOUR MYSQL USERNAME",
    "password": "YOUR MYSQL PASSWORD"
  }
```
1. Done!

## Usage

To use the API (in it's current state), you use the "/index.php" endpoint to access the database with the following formats:

### Get Requests

#### General Format

The general format for a GET request is as follows:

> index.php/table_name/col1+col2/ID?param=value

- __table_name__: Represents the name of the table you're looking to get results from
- __col1+col2__: The columns you wish to retrieve separated by a "+" symbol; If you wish for all columns to be returned, simply don't type them.
- __ID__: The ID of the specific row you want to be returned. If you don't want to get a specific row, simply don't type an ID
- __param=value__: You're able to specify certain parameters to narrow your response using the parameters listed below.

#### URL Parameters

|Parameter Name|Format|Purpose|
|-|-|-|
|limit|=n|Returns a maximum of n results starting from the first towards the last row
|order|=col1\|ASC;col2\|DESC|Orders the results of the query by the first column specified in the direction specified (ASC/DESC) and then orders by the second column. (Pseudosupports ordering by unlimited columns)|
|filters|=col1\|=\|val1;col2\|LIKE\|val2|Filters the query results to match all of the Conditions specified. Separate the column name, operator and value by a "\|" character and separate conditions by a ";". The operator must be a MySQL operator|

#### Examples

Fetch all rows for the users table
> index.php/users

Fetch the first and last names from the users table
> index.php/users/first_name+last_name

Fetch the user with an ID of 4
> index.php/users/4

Fetch the email of everyone with "John" as a first name
> index.php/users/email/?filters=first_name|LIKE|John

## Known Issues
- None (yet)

## Changelog
Will be updated when the first alpha version is released

## Credits
- Just me, Coby Ambrose @ AWG!
  - Website: [ambroseweb.co.uk](https://ambroseweb.co.uk "My Website")
  - Instagram: [@ambroseweb](https://www.instagram.com/ambrosewebgroup/ "My Instagram")