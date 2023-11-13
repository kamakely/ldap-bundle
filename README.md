### PURPOSE OF THIS BUNDLE
The goal of this bundle is to integrate the login provided by symfony with minimum possible effort.
It set all step provided by doc of symfony ldap in your place.
You just need to set parameters of your ldap server.

### INSTALLATION
```bash
composer require tounaf/ldap-bundle
```
### EXAMPLE CONFIGURATION

```yaml
tounaf_ldap:
    connection:
      host: "ldap.example.com"
      port: 389
      encryption: none # possible value tls/ssl/none, default is none
      options:
          protocol_version: 3
          referrals: false
    providers:
        pulse_ldap_provider:
            ldap:
                base_dn: "OU=Admin,DC=in,DC=com"
                search_dn: "CN=ogc-ass,OU=Service accounts,OU=Admin,DC=in,DC=com"
                search_password: "password"
                extra_fields: ['mail']
    form_login_ldap:
        dn_string: 'DOMAIN\{username}' # or {username}
        login_path: app_login
        check_path: app_login
        default_target_path: app_kama
```

### FULL CONFIGURATOIN REFERENCE
```yaml
tounaf_ldap:
    connection:
        # Ldap host
        host:                 ~ # Required
        # The port used by ldap
        port:                 '389' # Required
        # the encryption method: none/tls/ssl
        encryption:           none
        options:
            # Verstion of protocole
            protocol_version:     '3'
            # Verstion of protocole
            referrals:            false
    providers:
        # Prototype
        name:
            ldap:
                service:              Symfony\Component\Ldap\Ldap
                base_dn:              ~ # Required, Example: 'OU=Utilisateurs,DC=makeitpulse,DC=in,DC=axian-group,DC=com'
                search_dn:            ~ # Required, Example: 'CN=ogc-ass,OU=Service accounts,OU=Administrations,DC=ass,DC=in,DC=axian-group,DC=com'
                search_password:      null # Required, Example: your_favorit_password
                default_roles:        ROLE_USER # Required, Example: ROLE_USER
                uid_key:              sAMAccountName # Example: sAMAccountName
                extra_fields:         []
    form_login_ldap:
        # ds_string can be: DOMAIN\{username} or like just {username}
        dn_string:            '{username}' # Required, Example: 
        # route to login user
        login_path:           app_login
        # route to process login
        check_path:           app_login
        # route to redirect user when login success
        default_target_path:  ~

```