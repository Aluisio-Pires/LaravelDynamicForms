**LaravelDynamicForms**

Dynamic Forms for Laravel with validations.

## Requirements

- Laravel 9+

## Install
```
   composer require aluisio-pires/laravel-dynamic-forms
```

Register the provider class in "config/app.php"
```
   'providers' => [
    // Others ServiceProviders

    AluisioPires\LaravelDynamicForms\LaravelDynamicFormsServiceProvider::class,
],
```

Run the install command
```
   php artisan dynamic-forms:install
```


## Usage

**In your model class, use HasForms trait.**
Example:
   ```
   <?php

    namespace App\Models;
    
    use App\Traits\HasForms;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    
    class MyModel extends Model
    {
        use HasForms;
        use HasFactory;
        
        protected $fillable = [
            'form_id',
        ];
    }
   ```

Now you can create your own form with fields and validations. You can see the model field contents just like:
   ```
   $model->fieldName
   ```
You also can save the content just passing field names, like:
   ```
   $model->saveFields([
            'fieldName1' => [
                123,
                123,
            ],
            'fieldName2' => 'test',
        ]);
   ```