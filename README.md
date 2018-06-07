# yii2-json-log-file-target
Store log file as Json

## Usage

```php
'components' => [
    'log' => [
        'targets' => [
            [
                'class' => JsonFileTarget::class,
                'levels' => ['error', 'warning'],
                'decodeMessage' => false,
            ]
        ]
    ]
]
```