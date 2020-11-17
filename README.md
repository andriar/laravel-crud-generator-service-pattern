## GENERAL

Point of sales API apps for **Coffee Shop** or **UMKM** would be open soon !

## FRAMEWORK

This apps using latest update laravel 8 **5 November 2020**

## REQUIREMENT

1. composer
2. PHP 7.3
3. Coffee
4. Girlfriend(s)

## HOWTO

-   composer Install
-   php artisan passport:install --uuids
-   php artisan db:seed
-   php artisan serve

## HOWTO MAKE AN API

before u make an api, make sure you already made a migration file

`php artisan make:model create_{plural_table_name}_table`

then change id like this `$this->uuid('id')->primary()` and add `$table->softDeletes();`

### GENERATE API

`php artisan generate:crud {name_of_table_singular}`

this command will create some file (CONTROLLER, SERVICE, MODEL AND add list of API)

example:
`php artisan generate:crud Product`

make some files:
1. ProductController
2. ProductService
3. Product (Model)
4. add list of CRUD api Product

what u need to do is,
1. tidy list of API
2. insert some fillable field on Model
3. add some validation on controller when store and update data
4. **DONE!**


## API DOCUMENTATION

_**https://www.getpostman.com/collections/06d08b6df1595cd907cf**_

_**ENJOY !**_

## FILTER/PARAMS DOCUMENTATION

### JOIN

params join using to get data on table where has an relation, example

**USER_TABLE has relation with ROLE_TABLE** then params u need to throw :

`join=role` plural or not base on relation 1 to many or 1 to 1



next u cant join more than 1 table using point (.) to separate and comma (,) to add more than 1 join table

- `join=merchant.user` BASE_TABLE has relation to **MERCHANT_TABLE**, and **MERCHANT_TABLE** has relation to **USER_TABLE**
- `join=merchant.user,role` you will get relation **MERCHANT_TABLE** and **ROLE_TABLE**

### COUNT

Same as JOIN but you will get counted data from the relation, example :

`count=merchant` you will get data **merchant_count: 5**

`count=merchant,role`

### SORT

sorting base on field name, example:

`sort=name,ASC` after sort={field_name},ASC/DESC

### PER_PAGE & PAGE

to make pagination, example :

`per_page=5`

if u need to move to other page just add params `page`

`per_page=5&page=2`

### LIMIT

to limit how much data will shown, example :
`limit=5`

### WITH_TRASHED (boolean)

to show all data + archived data (soft deleted data), example :

`with_trashed=true`

### WITH_TRASHED (boolean)

to only archived data (soft deleted data), example :

`only_trashed=true`

### WHERE_HAS (array)

to get data where the filter is on the relation table. example:

`where_has[]=merchant,name,coffee` after where_has=['{relation_name},{field_name},{keyword}']

`where_has[]=merchant,name,coffee'&where_has[]=role,name,admin`

### FILTER (array)

to get filter data. example :

`filter[]=name,joko`

if more than 1 add 1 more keys with **AND** or **OR**

`filter[]=name,joko,AND&filter[]=is_active,1`

or

`filter[]=name,joko,OR&filter[]=is_active,1`
