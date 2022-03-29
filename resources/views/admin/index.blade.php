@extends('admin.layouts.admin')

@section('title', 'How to start?')

@section('content')
    <div class="card shadow mb-4">
        <?php
            $host = env('DB_HOST', '127.0.0.1');
            $port = env('DB_PORT', '3306');
            $user = env('DB_USERNAME', 'forge');
            $password = env('DB_PASSWORD', '');
            $database = env('DB_DATABASE', 'forge');

            if ($port == '')
                $port = 3306;
        ?>

        <div class="card-body">
            <p>Download <a href="https://www.spigotmc.org/resources/gsa-locklogin.75156/" target="_blank">LockLogin</a> from spigot</p>
            <p>Download LockLoginSQL module to enable azuriom support</p>
            <ul>
                <li><a href="https://karmaconfigs.ml/locklogin/modules">Option 1</a></li>
                <li><a href="https://karmarepo.ml/locklogin/modules">Option 2</a></li>
                <li><a href="https://karmadev.es/locklogin/modules">Option 3</a></li>
            </ul>
            <ol>
                <li>Put LockLogin.jar in yourServer/plugins/</li>
                <li>Start your server so LockLogin generates its folders</li>
                <li>Stop your server</li>
                <li>Put LockLoginSQL.jar in yourServer/plugins/LockLogin/plugin/modules/</li>
                <li>Edit the file <code>/plugins/LockLogin/config.yml</code></li>
                <code>
                    Encryption: <br>
                    ‍    ‍ Passwords: '{{config('hashing.driver')}}' <br>
                </code><br>
                <li>Edit the file <code>/plugins/LockLogin/plugin/modules/LockLoginSQL/config.yml</code></li>
                <code>
                    Driver: MySQL<br>
                    <br>
                    PrivateAzuriom: true ( optional if you want the users to be registered in this azuriom website to join the server )<br>
                    <br>
                    Connection:  <br>
                    ‍    ‍ Host: <?php echo $host; ?><br>
                    ‍    ‍ Port: <?php echo $port; ?> <br>
                    ‍    ‍ User: <?php echo $user; ?> <br>
                    ‍    ‍ Password: '<?php echo $password; ?>' <br>
                    ‍    ‍ Database: '<?php echo $database; ?>' <br>
                    ‍    ‍ Table: 'users' <br>
                </code><br>
            </ol>
        </div>
    </div>
@endsection
