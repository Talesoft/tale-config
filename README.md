
# Tale Config
**A Tale Framework Component**

# What is Tale Config?

A small configuration utility library.

There will soon be adapters (php, xml, yml, json etc.) and config-file management utilities.

Maybe DI. Maybe not.

# Installation

Install via Composer

```bash
composer require "talesoft/tale-config:*"
composer install
```

# Usage

```php

use Tale\ConfigurableInterface;
use Tale\ConfigurableTrait;

class DbConnection implements ConfigurableInterface
{
    use ConfigurableTrait;
    
    public function __construct(array $options = null)
    {
    
        $this->defineOptions([
            'host' => 'localhost',
            'user' => 'root',
            'password' => '',
            'encoding' => 'utf-8'
        ], $options);
        
        var_dump($this->_options);
        //etc., look at the source code. Docs will come soon
    }
}
```

