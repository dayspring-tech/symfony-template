# Dayspring Security Bundle

This bundle provides basic username/password authentication, forgot/reset password, and change password.

If your application requires additional information to be stored with the user, the recommended strategy is to create
a `UserProfile` model using Propel's inheritance features.
http://propelorm.org/Propel/documentation/09-inheritance.html#class-table-inheritance


Minimally, your `security.yml` should contain the following:
```
security:
    providers:
        dayspring:
            id: dayspring_security.user_provider
    encoders:
        Dayspring\SecurityBundle\Model\User:
            algorithm: bcrypt
            cost: 12
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        login:
            pattern:  ^/login$
            security: false
        secured_area:
            pattern:    ^/
            form_login:
                check_path: _login_check
                login_path: _login
                default_target_path: /_demo/secure
                provider: dayspring
            logout:
                path:   _logout
                target: /
            anonymous: ~
```