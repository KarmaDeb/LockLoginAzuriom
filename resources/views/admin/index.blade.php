@extends('admin.layouts.admin')

@section('title', 'How to start?')

@section('content')
    <div class="card shadow mb-4">
        <?php
            $port = env('DB_PORT', '3306');
            $current_step = 0;

            $steps = [
                '<p>Download <a href="https://www.spigotmc.org/resources/gsa-locklogin.75156/" target="_blank">LockLogin</a> from spigot</p>',
                '<p>Download LockLoginSQL module to enable azuriom support</p>
                <ul>
                    <li><a href="https://karmaconfigs.ml/locklogin/modules">Option 1</a></li>
                    <li><a href="https://karmarepo.ml/locklogin/modules">Option 2</a></li>
                    <li><a href="https://karmadev.es/locklogin/modules">Option 3</a></li>
                </ul>',
                '<li>Put LockLogin.jar in yourServer/plugins/</li>',
                '<li>Start your server so LockLogin generates its folders</li>',
                '<li>Stop your server</li>',
                '<li>Put LockLoginSQL.jar in yourServer/plugins/LockLogin/plugin/modules/</li>',
                '<li>Edit the file <code style="color: lime">/plugins/LockLogin/config.yml</code></li>
                <code>
                Encryption: <br>
                ‍    ‍ Passwords: \'{{config(\'hashing.driver\')}}\' <br>
                </code><br>',
                '<li>Edit the file <code style=\'color: lime\'>/plugins/LockLogin/plugin/modules/LockLoginSQL/config.yml</code></li>
                <code>
                    Driver: MySQL<br>
                    <br>
                    Azuriom: <br>
                    ‍    ‍ Private: true #Optional, by enabling this you restrict users to your forum registered users<br>
                    ‍    ‍ OnlyAdmin: false #Optiona, by enabling this only users with admin privileges will be able to join your server<br>
                    ‍    ‍ MinimalPower: 0 #Optional, by setting a value greater than zero, only users with or more than that amount of power will be able to join the server<br>
                    <br>
                    Connection:  <br>
                    ‍    ‍ Host: \'{{config(\'database.connections.mysql.host\')}}\'<br>
                    ‍    ‍ Port: '. $port .' <br>
                    ‍    ‍ User: \'{{config(\'database.connections.mysql.username\')}}\'<br>
                    ‍    ‍ Password: \'{{config(\'database.connections.mysql.password\')}}\' <br>
                    ‍    ‍ Database: \'{{config(\'database.connections.mysql.database\')}}\' <br>
                    ‍    ‍ Table: \'users\' <br>
                </code><br>'
            ];

            if ($port == '')
                $port = 3306;
        ?>

        <style>
            .card-body {
                color: white;
            }
        </style>

        <div class="card-body" style='background-color: #1e1e1e; border: solid 1px #1e1e1e; border-radius: 5px'>
            <p class='step_0'>Download <a href="https://www.spigotmc.org/resources/gsa-locklogin.75156/" target="_blank">LockLogin</a> from spigot</p>
            <p class='step_1' style="display: none">Download LockLoginSQL module to enable azuriom support</p>
            <ul class='step_1' style="display: none">
                <li><a href="https://karmaconfigs.ml/locklogin/modules">Option 1</a></li>
                <li><a href="https://karmarepo.ml/locklogin/modules">Option 2</a></li>
                <li><a href="https://karmadev.es/locklogin/modules">Option 3</a></li>
                <li><a href="https://backup.karmaconfigs.ml/locklogin/modules">Option 4</a></li>
                <li><a href="https://backup.karmarepo.ml/locklogin/modules">Option 5</a></li>
                <li><a href="https://backup.karmadev.es/locklogin/modules">Option 6</a></li>
            </ul>
            <p class='step_2' style="display: none">Put LockLogin.jar in yourServer/plugins/</p>
            <p class='step_3' style="display: none">Start your server so LockLogin generates its folders</p>
            <p class='step_4' style="display: none">Stop your server</p>
            <p class='step_5' style="display: none">Put LockLoginSQL.jar in yourServer/plugins/LockLogin/plugin/modules/</p>
            <p class='step_6' style="display: none">Edit the file <code style='color: lime'>/plugins/LockLogin/config.yml</code></p>
            <code class='step_6' style="display: none">
                Encryption: <br>
                 ‍    ‍ Passwords: '{{config('hashing.driver')}}' <br>
            </code><br>
            <p class='step_7' style="display: none">Edit the file <code style='color: lime'>/plugins/LockLogin/plugin/modules/LockLoginSQL/config.yml</code></p>
            <code class='step_7' style="display: none">
                Driver: MySQL<br>
                <br>
                Azuriom: <br>
                 ‍    ‍ Private: true <ins style='text-decoration: none; color: cyan'>#Optional, by enabling this you restrict users to your forum registered users</ins><br>
                 ‍    ‍ OnlyAdmin: false <ins style='text-decoration: none; color: cyan'>#Optiona, by enabling this only users with admin privileges will be able to join your server</ins><br>
                 ‍    ‍ MinimalPower: 0 <ins style='text-decoration: none; color: cyan'>#Optional, by setting a value greater than zero, only users with or more than that amount of power will be able to join the server</ins><br>
                <br>
                Connection:  <br>
                 ‍    ‍ Host: '{{config('database.connections.mysql.host')}}'<br>
                 ‍    ‍ Port: <?php echo $port; ?> <br>
                 ‍    ‍ User: '{{config('database.connections.mysql.username')}}'<br>
                 ‍    ‍ Password: '{{config('database.connections.mysql.password')}}' <br>
                 ‍    ‍ Database: '{{config('database.connections.mysql.database')}}' <br>
                 ‍    ‍ Table: 'users' <br>
            </code><br>

            <button type="button" id='next' class="btn btn-success">Next step!</button>

            <script type='text/javascript'>
                var step = 0;

                document.getElementById('next').addEventListener('click', function(event) {
                    step++;
                    
                    if (step <= 7) {
                        let prev = document.getElementsByClassName('step_' + (step - 1));
                        for (let i = 0; i < prev.length; i++) {
                            if (prev[i].id != 'step_2') {
                                prev[i].style.display = 'none';
                            }
                        }

                        let steps = document.getElementsByClassName('step_' + step);

                        for (let i = 0; i < steps.length; i++) {
                            steps[i].style.display = 'block';
                        }

                        if (step == 7) {
                            document.getElementById('next').innerHTML = "I'm done!";
                        }
                    } else {
                        for (let i = 0; i < 7; i++) {
                            let steps = document.getElementsByClassName('step_' + i);

                            for (let x = 0; x < steps.length; x++) {
                                steps[x].style.display = 'block';
                            }
                        }

                        document.getElementById('next').remove();
                    }
                });
            </script>
        </div>
    </div>
@endsection
