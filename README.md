# Clouding Tool

```
  _____ _                 _ _
 / ____| |               | (_)
| |    | | ___  _   _  __| |_ _ __   __ _
| |    | |/ _ \| | | |/ _` | | '_ \ / _` |
| |____| | (_) | |_| | (_| | | | | | (_| |
 \_____|_|\___/ \__,_|\__,_|_|_| |_|\__, |
                                     __/ |
                                    |___/
```

> Built with [Laravel Zero](https://github.com/laravel-zero/laravel-zero)

## Installation

1. Clone repository
```bash
git clone git@github.com:cloudingcity/tool.git
cd tool
```

2. Install dependencies via Composer
```bash
composer install
```

3. Configure environment file
```bash
cp .env.example .env
vim .env
```

4. Execute console
```bash
php clouding
```

## Download phar

1. Download to your `$PATH` directory
```bash
curl -O https://raw.githubusercontent.com/cloudingcity/tool/master/builds/clouding
curl -O https://raw.githubusercontent.com/cloudingcity/tool/master/.env.example
```

2. Configure environment file
```bash
cp .env.example .env
vim .env
```

3. Execute console
```
clouding
```

### Self update
```
clouding self-update
```

