# Chain Explorer Light for Symfony

### Configure

Add your Api Key in your .ddev/config.yaml

```yaml
 - BLOCKCYPHER_TOKEN=put_your_token_here
```


## Install

```
ddev start
ddev composer install
ddev php bin/console doctrine:migrations:migrate
ddev describe
```

