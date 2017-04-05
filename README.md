# Doorman

Probably dont need the redemptions table

Invites
===
* id
* code (unique)
* for (nullable email)
* max (int, max redemptions, 0 = unlimited)
* redemptions (int)
* valid_until (date, nullable for no expiry)

````php
Doorman::check(code);
Doorman::check(code, email);

Doorman::redeem(code);
Doorman::redeem(code, email);

Doorman::generate(email = null);
Doorman::generateFor(email);

Doorman::times(5)->generate();
````
