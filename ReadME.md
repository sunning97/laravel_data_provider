### Data Provider For Laravel

For any problem please contact me: giangnguyen.neko.130@gmail.com

This library is incomplete and it may have many errors, please make it become complete. Thank you

### Usage
First create data layer
```php
namespace Provider;
use Kuroneko\Yii2Provider\Abstracts\BaseLayerAbstract;

class CatElasticLayer extends BaseLayerAbstract
{
    //
}
```

```php
namespace Provider;
use Kuroneko\Yii2Provider\Abstracts\BaseLayerAbstract;

class CatRedisLayer extends BaseLayerAbstract
{
    //
}
```

```php
namespace Provider;
use Kuroneko\Yii2Provider\Abstracts\BaseLayerAbstract;

class CatDBLayer extends BaseLayerAbstract
{
    //
}
```

Then Create Provider class extend from Kuroneko\Yii2Provider\Abstracts\BaseProviderAbstract

**Recommendation**: Class should implement interface for strict code
You can use provider as yii component. just add it to config

```php
use Kuroneko\Yii2Provider\Abstracts\BaseProviderAbstract;

class CatProvider extends BaseProviderAbstract implements CatProviderInterface
{
    /**
     * @return string
     */
    public function method(): string
    {
        return 'elastic'; //define main data method
    }

    /**
     * Define Class corresponding to the method here
     * @return array
     */
    public function mapMethod(): array
    {
        return [
            'db' => 'Provider\CatDBLayer',
            'redis' => 'Provider\CatRedisLayer',
            'elastic' => 'Provider\CatElasticLayer',
        ];
    }

    /**
     * Define method with use another layer in here
     * @return array
     */
    public function except(): array
    {
        return [
            'getCatEye' => 'redis'
        ];
    }
    
    // define function to get data
    /**
     * @inheritDoc
     */
    public function getCatEye($catId, $catType)
    {
        return parent::call('getCatEye', ...func_get_args());
    }


}
```