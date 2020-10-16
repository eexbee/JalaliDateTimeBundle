
# JalaliDateTimeBundle
Brings Jalali (Persian/Iranian) DateTime to Symfony/Twig


## Requirements
- symfony > 3.4

## Installation
`composer require eexbee/jalali-date-time-bundle`

## Usage
This bundle provides Jalali DateTime tools to be used inside your Controllers, Twig templates and forms.

### 1. Twig
Use `j_datetime_format` twig filter to convert php DateTime object into Jalali format
``` twig
{# templates/frontend/home.html.twig #}
...
    {{ datetimeObject|j_datetime_format }} {# ا۲۲:۰، ۰۹ مهر ۱۳۹۹ #}
...
```
You can Also pass several parameters to the filter to customize the output.
Filter definition looks like this:
``` php
...
    j_datetime_format(
        \DateTime $dateTime, //php DateTime Object
        $format = 'H:i ,d F Y', //Arbitrary format (checkout format table)
        $timeZone = 'Asia/Tehran', //Timezone
        $lang = 'fa' //Output number characters (fa or en)
    )
...
```

Example:
``` twig
{# templates/frontend/home.html.twig #}
...
    {{ datetimeObject|j_datetime_format('Y-m-d H:i:s', "Europe/Paris", "en") }}
    {# output: 1399-07-09 12:27:49 #}
...
```
[Checkout availabe formats](#format-table)


### 2. Form
soon


### 3. Service
Jalali DateTime service is automatically ready to use due to the symfony service management and dependency injection mechanism.


### Format Table
