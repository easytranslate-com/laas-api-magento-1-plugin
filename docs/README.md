# EasyTranslate Magento 1 Connector

This Magento 1 extension integrates EasyTranslate into Magento 1.

Mind that you need to have an account with EasyTranslate on Language-as-a-Service. Please send an e-mail to
info@easytranslate.com to learn more.

## Description

This Magento 1 extension integrates EasyTranslate into Magento 1. It enables you to translate products, categories, CMS
blocks and CMS pages via EasyTranslate.

## Workflow

The following diagram gives a rough overview of the workflow:

![EasyTranslate Magento 1 Workflow](images/easytranslate-m1-connector.jpg "EasyTranslate Magento 1 Workflow")

### Initial Configuration

Go to System > Configuration > Services > EasyTranslate Connector:

![EasyTranslate Magento 1 Configuration](images/easytranslate-m1-connector-configuration.png "EasyTranslate Magento 1 Configuration")

First, insert the API credentials you either get from the EasyTranslate support or from
your [EasyTranslate Account](https://platform.easytranslate.com/) under Settings >
API. Then, for each entity, you can decide which attributes should be translated via EasyTranslate. Save your
configuration.

Make sure that the default Magento cronjob is configured and runs correctly. To check
this, [Aoe_Scheduler](https://github.com/AOEpeople/Aoe_Scheduler) might come in handy.

### Create Projects / Access Existing Projects

Go to System > EasyTranslate Projects. Click the "Add Project" button on the upper right corner. Add the basic project
information and click "Save and Continue Edit":

![EasyTranslate Magento 1 Create Project](images/easytranslate-m1-connector-create-project.png "EasyTranslate Magento 1 Create Project")

### Add Content To Project

Open the project from System > EasyTranslate Projects. Click on the entity type you want to add on the left column.
Then, change the filter to "Any", search for the entities you want to add and select the checkbox. Then, save the
project.

![EasyTranslate Magento 1 Add Content To Project](images/easytranslate-m1-connector-add-content-to-project.png "EasyTranslate Magento 1 Add Content To Project")

### Send Project To EasyTranslate

As soon as you are finished with adding content to your project, you can send it to EasyTranslate using the "Send To
EasyTranslate" button in the project view.

### Accept / Decline Price [optional]

If the estimated price for the project is above your configured threshold at EasyTranslate, you have the possibility to
accept or decline the price inside of Magento. As soon as the price is available and above your personal threshold, you
will see respective buttons in the project view:

![EasyTranslate Magento 1 Accept / Decline Price](images/easytranslate-m1-connector-accept-decline-price.png "EasyTranslate Magento 1 Accept / Decline Price")

### Import of Translated Content

If the estimated price for the project is below your threshold, or you accepted the price, the content will be
translated. As soon as the translations are available, EasyTranslate will notify your shop about it. When your default
Magento cronjob is configured correctly, translations will then be automatically imported after a few hours.

## Compatibility

This extension is compatible with Magento 1.9.4.5.

It may also be compatible with older versions, but we strongly recommend to only use the latest version of Magento 1.

It may also be compatible with OpenMage, but this has not been explicitly tested.

## Installation Instructions

The installation procedure highly depends on your setup. In any case, you should use a version control system like git
and test the installation on a development system.

### Composer Installation

1. `composer require easytranslate/m1-connector`
2. `n98-magerun.phar sys:setup:run`
3. `n98-magerun.phar cache:flush`

### Manual Installation

1. Unzip the downloaded files.
2. Copy the contents of the `src` directory from the unzipped files to the root directory of your shop.
3. `n98-magerun.phar sys:setup:run`
4. `n98-magerun.phar cache:flush`

## Uninstallation

The uninstallation procedure depends on your setup:

### Uninstallation After Composer Installation

1. `composer remove easytranslate/m1-connector`
2. `n98-magerun.phar cache:flush`

### Uninstallation After Manual Installation

1. `rm -r app/code/community/EasyTranslate/`
2. `rm app/design/adminhtml/default/default/layout/easytranslate.xml`
3. `rm app/etc/modules/EasyTranslate_Connector.xml`
4. `rm app/locale/de_DE/EasyTranslate_Connector.csv`
5. `rm app/locale/en_US/EasyTranslate_Connector.csv`
6. `rm -r js/easytranslate/`
7. `rm -r lib/EasyTranslate/`
8. `n98-magerun.phar cache:flush`

## Support

If you have any issues with this extension, feel free to open an issue
on [GitHub](https://github.com/easytranslate-com/laas-api-magento-plugin/issues).

## Licence

[Open Software License 3.0](https://opensource.org/licenses/OSL-3.0)

## Copyright

&copy; 2020 EasyTranslate A/S
