# R3D Product Test - Drupal 8 version

## Requeriments & assumptions
* Drupal 8 site installed and listening on http://localhost:8888/drupal
* drupal root `/Applications/MAMP/htdocs/drupal`
* MySql 

## Dependencies

The following dependencies are required for the email notification functionality. 

Install first swiftmailer library using composer at the root of drupal folder:
``` bash
$ composer require swiftmailer/swiftmailer
```

Then install and enable the following modules using drupal admin UI

 * https://www.drupal.org/project/mailsystem ( https://ftp.drupal.org/files/projects/mailsystem-8.x-4.1.tar.gz )
 * https://www.drupal.org/project/swiftmailer (
 https://ftp.drupal.org/files/projects/swiftmailer-8.x-1.0-beta1.tar.gz )

Once enabled, visit http://localhost:8888/drupal/admin/config/system/mailsystem add set `swiftMailer` as default formatter and sender ( or set it my module if desired )

Configure SMTP transport in: http://localhost:8888/drupal/admin/config/swiftmailer/transport - See https://github.com/diazalej/r3d_product_symfony/blob/master/app/config/parameters.yml.dist for inspiration of the setting using while testing locally 

## Installing the module

``` bash
$ cd /Applications/MAMP/htdocs/drupal
$ mkdir -p modules/custom
$ cd modules/custom
$ git clone git@github.com:diazalej/r3d_product_d8.git
```

Once the repo is clone in the custom modules folder, visit http://localhost:8888/drupal/admin/modules you should be able to see and enable the `r3dproducts` module.  


## Accessing the form
Visit http://localhost:8888/drupal/r3dproducts to the form and list of current products.

