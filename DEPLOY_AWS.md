# Deployment to AWS

This template project is designed to deploy to AWS via Opsworks.


## Create VPC Security Groups for EC2 and RDS
Create two security groups, one for the webserver and the other for RDS. Set the RDS security group to allow inbound access from the EC2 security group.


## Create an RDS instance
Assign it the RDS security group you just created.


## Create a new Stack
- Default operating system: Custom Linux AMI
- Chef version: 11.10
- Use custom Chef cookbooks: Yes
- Repository URL: `git@bitbucket.org:dayspring-tech/cookbooks.git`
    - Create a SSH keypair and add to dayspring-tech/cookbooks as a deploy key
- Manage Berkshelf: Yes

### Stack Settings
These are minimal settings to get going. Look for places where you need to customize the settings.
```
{
    "symfony": {
        "root": "symfony",
        "env": "prod",
        "frontend": "app.php"
    },
    "php_ini": {
        "date.timezone": "UTC",
        "default_charset": "UTF-8",
        "expose_php": "Off"
    },
    "composer": {
        "install_globally": true,
        "github_oauth": "[GITHUB OAUTH TOKEN]"
    },
    "system": {
        "timezone": "UTC"
    },
    "deploy": {
        "[SHORT APP NAME]": {
            "keep_releases": 3,
            "delete_cached_copy": false
        }
    },
    "mod_php5_apache2": {
        "packages": [
            "php",
            "php-mbstring",
            "php-soap",
            "php-pdo",
            "php-intl",
            "php-ldap",
            "php-mysql",
            "php-sqlite3",
            "php-xml",
            "php-common",
            "php-xmlrpc",
            "php-devel",
            "php-cli",
            "php-pear-Auth-SASL",
            "php-mcrypt",
            "php-pecl-memcache",
            "php-pear",
            "php-pear-XML-Parser",
            "php-pear-Mail-Mime",
            "php-pear-DB",
            "php-pear-HTML-Common",
            "php-opcache"
        ]
    },
    "newrelic": {
        "license": "[NEWRELIC LICENSE KEY]",
        "application_monitoring": {
            "enabled": true,
            "app_name": "[APP NAME]"
        },
        "php_agent": {
            "config_file": "/etc/php.d/newrelic.ini"
        }
    },
    "datadog": {
        "api_key": "[DATADOG API KEY]",
        "application_key": "[DATADOG APPLICATION KEY]",
        "apache": {
            "instances": [
                {
                    "status_url": "http://localhost/server-status?auto"
                }
            ]
        },
        "mysql": {
            "instances": [
                {
                    "server": "[RDS HOSTNAME]",
                    "user": "[DATADOG USER FOR RDS]",
                    "pass": "[PASSWORD]"
                }
            ]
        }
    }
}
```


## Create a PHP Layer
### PHP Layer Recipes
- Setup
  - dt_opsworks::system_timezone
  - composer
  - dt_opsworks::php_ini
  - clamav
  - newrelic
  - newrelic::php_agent
  - newrelic::server_monitor_agent
  - datadog::dd-agent
  - datadog::apache
  - datadog::mysql
- Deploy
  - dt_opsworks::server_status

### PHP Layer Network
- Automatically assign IP addresses
  - Public IP address: Yes

### PHP Layer Security
Add the EC2 security group you created earlier.


## Register RDS Layer
Add an RDS Layer, selecting the RDS instance you created.


## Add an Application
Provide the URL to your repo and create an SSH keypair for deployment. Select the RDS instance you registered, and enter the database name.


## Add an Instance
Add a new instance.
Select the `Amazon_Linux_2015.09_REMI_PHP5.6` Custom AMI
Recommended minimum size: t2.small


## Start your Instance
Start the instance you created. It should run through the setup steps and have a running app in a few minutes.