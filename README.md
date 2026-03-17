# Lageg Laravel Reporter

Reporter is a php laravel library to report build orchestrate using the **Reporter Facade**. You can define your own drivers for the report build with the lib of your preference, such as **Mpdf**, **Spatie Laravel PDF**, **Laravel Excel** and many others.

### Instalation and configuration

You can install the package by using the following command:

```bash
composer require lageg/reporter
```

By default the package will use the default driver set on config/reporter.php.

You can publish the config with the following command:

```bash
php artisan vendor:publish --tag=reporter
```

You can define the default driver for whatever you want:

```php
/**
 * The default driver
 */
'default_driver' => 'pdf',
```

If in your application you want to use different drivers, you can set the configuration for each driver you want to use:

```php
'drivers' => [
    'csv' => [
        'class' => MyCsvDriver::class,
        'config' => []
    ]
]
```

### Drivers

Here is an example of the implementation of a custom driver, the driver here uses the Mpdf lib to generate a pdf file. The driver implicit require the exportable to register an HtmlComponent, the component that will be used to get the HTML data to build the pdf file.

```php
class MpdfDriver implements Driver
{
    public function generate(Exportable $exportable, array $config = []): Report
    {
        $mpdf = new Mpdf();
        $html = $exportable->query(HtmlComponent::class);

        if (!$html) {
            throw new Exception('Exporter must provide an html component');
        }

        $mpdf->writeHtml($html->value());

        $content = $mpdf->output();

        return new Report(
            $content,
            'application/pdf',
            $exportable->getFilename() . '.pdf'
        );
    }
}
```

### Components

You can use Componets to share data across layers in your application, while your exportable can access and manipulate Models, API's or external files, in your driver you just want to use the data, and you can do so by using Components.

In order to use the component in your driver you should first **register** the component in the exportable. The base **Exporter** class provides four function to interact with components: **components**, **register**, **has** and **query**.

```php
class ExampleWithHtmlComponent extends Exporter

    public function __construct()
    {
        $this->register($this->html());
    }

    private function html()
    {
        return new HtmlComponent("<h2>Here is all the HTML</h2>");
    }
}
```

You can also use **alias** for register the component

```php
class ExampleWithComponentAlias extends Exporter

    public function __construct()
    {
        $this->register(
            component: new HtmlComponent("<p>here is an example</p>"),
            alias: 'example'
        );
    }
}
```

```php
//Driver

$component = $exportable->query('example'); //HtmlComponent

//you can also use the class name resolution
$component = $exportable->query(HtmlComponent::class); //HtmlComponent

//a not registered component will return null
$component = $exportable->query(NotRegisteredComponent::class); //null
```

### Facade

The convenient way to orchestrate the build of your report is by using the **Reporter Facade**.

```php
$exportable = new ExampleExportable();

$report = Reporter::make($exportable)->generate();
```

You can also specify the driver you want to use to build the report.

```php
//using a configured driver
$report = Reporter::make($exportable)
    ->using('xlsx')
    ->generate();

//using class name resolution
$report = Reporter::make($exportable)
    ->using(MyCustomDriver::class)
    ->generate();

//using the default driver
$report = Reporter::make($exportable)->generate();
```

You can also provide custom configuration for your driver by using the **config** method:

```php
$exportable = new ExampleExportable();

$config = ['orientation' => 'Landscape'];

$report = Reporter::make($exportable)
    ->using(MyCustomDriver::class)
    ->config($config)
    ->generate();
```

You can define default configurations in the config/reporter.php file:

```php
'drivers' => [
    'pdf' => [
        'class' => MyPdfDriver::class,
        'config' => [
            'orientation' => 'Landscape',
            'format' => 'a4',
            'margins' => ['24mm', '24mm', '24mm', '24mm']
        ]
    ]
]
```

You are free to define the config the way you want and use it in your driver

```php
class MyCustomPdfDriver implements Driver
{
    public function generate(Exportable $exportable, array $config = []): Report
    {
        $pdf = new Pdf();

        if ($orientation = $config['orientation'] ?? null) {
            $pdf->orientation($orientation);
        }

        if ($format = $config['format'] ?? null) {
            $pdf->format($format);
        }

        //generate the file...
    }
}
```

The generate method returns a Report instance that can be used to respond, download, store, or output the generated report.

```php
$report = Facade::make($exportable)->generate();

// Returns a response that serves the generated file from a temporary location
return $report->response();

// Returns an HTTP response that forces the file download with proper headers
return $report->download();

// Stores the report content on the given disk and path, returning the stored path
$path = $report->store('s3', 'reports');

// Writes the report to a temporary file and returns its full path
$path = $report->output();
```