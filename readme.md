## HOW TO ##


### Migrations ###



    php artisan make:migration:schema create_tree_binary_table --schema="username:string, email:string:unique" --model=false



    php artisan make:migration:schema remove_user_id_from_tree_binary_table --schema="user_id:integer"



    php artisan make:migration:schema add_user_id_from_tree_binary_table --schema="user_id:integer"
    



    php artisan make:migration:schema create_posts_table
    php artisan make:migration:schema create_posts_table --schema="title:string, body:text, excerpt:string:nullable"
    php artisan make:migration:schema remove_excerpt_from_posts_table --schema="excerpt:string:nullable"


    artisan migrate:rollback --pretend -vv
    
Foreign keys

    php artisan make:migration:schema create_posts_table --schema="user_id:integer:foreign, title:string, body:text"



username:string
body:text
age:integer
published_at:date
excerpt:text:nullable
email:string:unique:default('foo@example.com')

Seeders 

    php artisan make:seed posts