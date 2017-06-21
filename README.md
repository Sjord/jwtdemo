## Vulnerable JWT implementations

Article: [Attacking JWT authentication](https://www.sjoerdlangkemper.nl/2016/09/28/attacking-jwt-authentication/).

Demo pages:
* [HS256](http://demo.sjoerdlangkemper.nl/jwtdemo/hs256.php)
* [RS256](http://demo.sjoerdlangkemper.nl/jwtdemo/rs256.php)

Attacks:
* Change the algorithm from HS256 to none.
* Change the algorithm from RS256 to HS256, and use the [public key](public.pem) as [the secret key for the HMAC](publickeyhs256.php).
* Crack the HMAC key using [John the Ripper](https://github.com/magnumripper/JohnTheRipper).

