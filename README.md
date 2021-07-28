# DogeCash Payment API and Checker

## Methods

### ?rate=\<string\>
---------------------------
Parameters:
- rate: Currency symbol ex. USD, AUD

Returns:
```json
{
  "result": <float>,
  "error": <string>
}
```
---------------------------

### ?address=\<string\>&amount=\<float\>&otime=\<int\>&tx=\<string\>&conf=\<int\>&mtime=\<int\>
-----------------------------------------------------------------------------------------------
Parameters:
- address: Valid DogeCash address as a string
- amount: Amount of DOGEC as a float
- otime: Order time as an integer
- tx: Transaction id or status as a string
- conf: Minimum number of confirmations required as an integer
- mtime: Maximum time in minutes as in integer

Returns if unsuccessful:
```json
{
  "status": <string>
}
```

Returns if undetected:
```json
{
  "status": <string>,
  "message": <string>
}
```

Returns if detected:
```json
{
  "status": <string>,
  "message": <string>,
  "transaction_id": <string>,
  "confirmations": <int>
}
```
