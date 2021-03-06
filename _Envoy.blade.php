{{-- Define all our servers --}}
@servers(['staging' => '', 'production' => ''])

@setup
{{-- The timezone your servers run in --}}
$timezone = 'Europe/Amsterdam';

{{-- The base path where your deployments are sitting --}}
$path = '/var/www/site.com/htdocs';

{{-- The git repository location --}}
$repo = '';

{{-- The git branch to deploy --}}
$branch = 'master';

{{-- The number of releases to keep --}}
$keep = 6;

{{-- Is the HTMLPurifier library installed --}}
$hasHtmlPurifier = true;

{{-- Files and direcrtories that need permissions of 755 and www-data as owner --}}
$chmods = [
'app/storage',
'app/database/production.sqlite',
'public',
];

{{-- All directories symlinked to the shared folder --}}
$symlinks = [
'storage/views'    => 'app/storage/views',
'storage/sessions' => 'app/storage/sessions',
'storage/logs'     => 'app/storage/logs',
'storage/cache'    => 'app/storage/cache',
];

{{-- DO NOT EDIT BELOW THIS LINE --}}
$date    = new DateTime('now', new DateTimeZone($timezone));
$release = $path .'/releases/'. $date->format('YmdHis');
@endsetup

{{-- Clone task, creates release directory, then shallow clones into it --}}
@task('clone', ['on' => $on])
mkdir -p {{ $release }}

git clone --depth 1 -b {{ $branch }} "{{ $repo }}" {{ $release }}

echo "Repository has been cloned"
@endtask

{{-- Updates composer, then runs a fresh installation --}}
@task('composer', ['on' => $on])
composer self-update

cd {{ $release }}

composer install --no-interaction --no-dev --prefer-dist

echo "Composer dependencies have been installed"
@endtask

{{-- Set permissions for various files and directories --}}
@task('chmod', ['on' => $on])
@foreach($chmods as $file)
    chmod -R 755 {{ $release }}/{{ $file }}

    chmod -R g+s {{ $release }}/{{ $file }}

    chown -R www-data:www-data {{ $release }}/{{ $file }}

    echo "Permissions have been set for {{ $file }}"
@endforeach

@if($hasHtmlPurifier)
    chmod -R 777 {{ $release }}/vendor/ezyang/htmlpurifier/library/HTMLPurifier/DefinitionCache/Serializer
@endif

echo "Permissions for HTMLPurifier have been set"
@endtask

{{-- Symlink some folders --}}
@task('symlinks', ['on' => $on])
@foreach($symlinks as $folder => $symlink)
    ln -s {{ $path }}/shared/{{ $folder }} {{ $release }}/{{ $symlink }}

    echo "Symlink has been set for {{ $symlink }}"
@endforeach

echo "All symlinks have been set"
@endtask

{{-- Set the symlink for the current release --}}
@task('update-symlink', ['on' => $on])
rm -rf {{ $path }}/current

ln -s {{ $release }} {{ $path }}/current

echo "Release symlink has been set"
@endtask

{{-- Migrate all databases --}}
@task('migrate', ['on' => $on])
php {{ $release }}/artisan migrate
@endtask

{{-- Just a done message :) --}}
@task('done', ['on' => $on])
echo "Deployment finished successfully!"
@endtask

{{-- Run all deployment tasks --}}
@macro('deploy')
clone
composer
chmod
migrate
symlinks
update-symlink
done
@endmacro