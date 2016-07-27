@servers(['test'=>'root@btcc.lxc'])


@macro('deploy')
git
folders
composer
artisan
links
@endmacro


@task('git')
    cd  /home/deploy
    rm -rf btcc-old
    mv -f btcc btcc-old
    git clone -b todeploy git@bitbucket.org:cagenic/btcc.git
@endtask


@task('folders')
cd /home/deploy
chown -hR www-data btcc
cp .env btcc/
cd btcc
mkdir bootstrap/cache
chmod -R 777 bootstrap/cache
chmod -R 777 storage/

@endtask

@task('mybad')
cd /home/deploy
mkdir btcc
{{--chmod -R 777 bootstrap/cache
chmod -R 777 storage/--}}
@endtask

@task('composer')
cd /home/deploy/btcc
composer install
@endtask


@task('db')
cd /home/deploy/btcc
php artisan down
php artisan migrate
php artisan db:seed
php artisan up
@endtask


@task('dbup')
cd /home/deploy/btcc
php artisan up
php artisan migrate
php artisan db:seed
@endtask

@task('artisan')
cd /home/deploy/btcc
php artisan db:seed
@endtask


@task('links')
cd /home/deploy/btcc
chmod -R 777 storage/
rm -rf  /hosts/btcc.lxc/web
ln -fns /home/deploy/btcc/public /hosts/btcc.lxc/web
cd /hosts/btcc.lxc/web
chmod +x index.php
@endtask



@task('tests')
cd /home/deploy/btcc
vendor/bin/phpunit
@endtask
