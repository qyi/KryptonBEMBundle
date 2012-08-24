## Installation

5 steps for complete installation:

1. Download KryptonBemBundle using composer
2. Enable the Bundle
3. Configure assetic bundles
4. Configure the KryptonBemBundle
5. Install Bem tools

### Step 1: Download KryptonBEMBundle using composer

Add KryptonBEMBundle in your composer.json:

```js
{
    "require": {
        "krypton/bembundle": "*"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update krypton/bembundle
```

Composer will install the bundle to your project's `vendor/krypton` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Krypton\BEMBundle\KryptonBEMBundle(),
    );
}
```

### Step 3: Configure assetic bundles

Bem templates build based on assetic assets.
Add bundles which has templates and blocks to assetic bundles configuration:

assetic:
    ...
    bundles:        [ KryptonBEMBundle, AdmeDemoBundle ]


### Step 4: Configure the KryptonBEMBundle

Configuration in your config.yml:

# app/config/config.yml
krypton_bem:
    node_modules: /usr/local/lib/node_modules
    bem_bl: %kernel.root_dir%/../../vendor/bem/bem-bl
    levels:
        - %kernel.root_dir%/../../vendor/bem/bem-bl/blocks-common
        - %kernel.root_dir%/../../vendor/bem/bem-bl/blocks-desktop
    filters:
        bem: 
            bin:        /usr/local/bin/bem

### Step 5: Install Bem tools

Information for  [bem tools](https://github.com/bem/bem-tools).
(KryptonBEMBundle tested with  bem@0.5.2 ometajs@2.1.x xjst@0.2.21 versions of packages)