

### Before production

```sh
    # 1 .env
    QUEUE_CONNECTION=database 
    # 2. Generate the Jobs Table
    php artisan queue:table
    php artisan migrate
    # 3. Creating and Dispatching Jobs | Create job
    php artisan make:job SendWelcomeEmail
    # 4. where work happens
    public function handle()
    {
        Mail::to($this->user->email)->send(new WelcomeEmail($this->user));
    }
    # 5. Creating the Welcome Email
    php artisan make:mail WelcomeEmail
    # 6. Email Blade View:
    touch mail.php
    # 6. Dispatch the Job
    SendWelcomeEmail::dispatch($user);
    # 7. Processing the Queue
    php artisan queue:work

```

### Configuring a Supervisor to Run Queue Workers
A queue worker is a process that continually checks the queue for new jobs and executes them.
While you can start a worker manually, you’ll want something more robust for production. 
Supervisor is a process manager that automatically restarts your worker if it fails, providing reliable job processing.
- Supervisor manages queue workers in the background on Linux systems.
```sh
    # 1. Install Supervisor:
    sudo apt-get install supervisor

```
- Create a Supervisor Configuration File: This file specifies how Supervisor will manage the queue worker.
- Location: /etc/supervisor/conf.d/laravel-worker.conf
```sh
    [program:laravel-worker]
    process_name=%(program_name)s_%(process_num)02d
    command=php /path-to-your-project/artisan queue:work --sleep=3 --tries=3
    autostart=true
    autorestart=true
    user=your-username
    numprocs=3
    redirect_stderr=true
    stdout_logfile=/path-to-your-project/worker.log
```