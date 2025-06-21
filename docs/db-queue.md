Okay, excellent choice for simplicity and isolation on a multi-user VPS\! Using the Database queue with Supervisor for each Laravel application is a robust approach that prevents conflicts.

Here's a step-by-step guide, assuming you have two Laravel applications on your VPS: `project_alpha` and `project_beta`. Each project will have its own database and its own dedicated queue worker.

-----

### Goal: Set up Database Queues with Supervisor for two independent Laravel applications (`project_alpha` and `project_beta`) on a VPS.

**Prerequisites:**

  * You have SSH access to your VPS.
  * PHP, Composer, and your web server (Nginx/Apache) are already set up and serving both Laravel applications.
  * Each Laravel application (`project_alpha` and `project_beta`) is in its own directory (e.g., `/var/www/project_alpha` and `/var/www/project_beta`).
  * Each application has its own dedicated database configured in its respective `.env` file and `config/database.php`.

-----

### Step-by-Step Guide

#### Step 1: Prepare Each Laravel Application (Locally & on VPS)

For **EACH** Laravel application (`project_alpha` and `project_beta`):

1.  **Configure Queue Driver:**

      * Open the `.env` file for the project (e.g., `/var/www/project_alpha/.env`).
      * Set the `QUEUE_CONNECTION` to `database`:
        ```dotenv
        QUEUE_CONNECTION=database
        ```
      * Ensure your database connection details (`DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) are correct and point to that project's specific database.

2.  **Create Queue Tables:**

      * Navigate to the project's root directory via SSH:
        ```bash
        cd /var/www/project_alpha
        ```
      * Run the Artisan commands to create the `jobs` and `failed_jobs` tables in *that project's specific database*:
        ```bash
        php artisan queue:table
        php artisan migrate
        php artisan queue:failed-table # Creates table for failed jobs (recommended)
        php artisan migrate
        ```
      * **Repeat these two steps for `project_beta`**.

    *Explanation:* This sets up the necessary database tables where Laravel will store the pending and failed queue jobs for each application independently.

#### Step 2: Install Supervisor on the VPS

This needs to be done only **once** on your VPS.

1.  **Update Package List:**
    ```bash
    sudo apt update
    ```
2.  **Install Supervisor:**
    ```bash
    sudo apt install supervisor
    ```
    *Explanation:* Supervisor is a process manager that will keep your queue workers running constantly and automatically restart them if they crash.

#### Step 3: Configure Supervisor for Each Laravel Application

You'll create a separate Supervisor configuration file for each project. This is key for isolation.

1.  **Navigate to Supervisor Configuration Directory:**

    ```bash
    cd /etc/supervisor/conf.d/
    ```

2.  **Create Config File for `project_alpha`:**

      * Open a new file (e.g., `project_alpha_worker.conf`) using a text editor:

        ```bash
        sudo nano project_alpha_worker.conf
        ```

      * Paste the following content, replacing `/var/www/project_alpha` with the actual path to your `project_alpha`, and `www-data` (or your specific user for that project):

        ```ini
        [program:project_alpha_worker]
        process_name=%(program_name)s_%(process_num)02d
        command=php /var/www/project_alpha/artisan queue:work --sleep=3 --tries=3 --timeout=3600 --daemon
        autostart=true
        autorestart=true
        user=www-data ; Use the user that owns project_alpha's files (e.g., www-data or your specific user)
        numprocs=1 ; Number of concurrent worker processes for this project. Start with 1.
        redirect_stderr=true
        stdout_logfile=/var/www/project_alpha/storage/logs/queue_worker.log
        stopwaitsecs=3600 ; Time (in seconds) to wait for a job to finish before killing the worker process
        ```

      * Save and close the file (Ctrl+X, Y, Enter for Nano).

3.  **Create Config File for `project_beta`:**

      * Open a new file (e.g., `project_beta_worker.conf`):

        ```bash
        sudo nano project_beta_worker.conf
        ```

      * Paste similar content, ensuring all paths and the `user` are correct for `project_beta`:

        ```ini
        [program:project_beta_worker]
        process_name=%(program_name)s_%(process_num)02d
        command=php /var/www/project_beta/artisan queue:work --sleep=3 --tries=3 --timeout=3600 --daemon
        autostart=true
        autorestart=true
        user=www-data ; Use the user that owns project_beta's files
        numprocs=1
        redirect_stderr=true
        stdout_logfile=/var/www/project_beta/storage/logs/queue_worker.log
        stopwaitsecs=3600
        ```

      * Save and close the file.

    *Explanation:* Each `.conf` file defines a separate Supervisor "program" specific to one Laravel application. This ensures `project_alpha`'s worker only processes `project_alpha`'s jobs from `project_alpha`'s database, and the same for `project_beta`.

      * `command`: The actual Artisan command to start the queue worker.
      * `--sleep=3`: How long to wait if no jobs are found (seconds).
      * `--tries=3`: How many times to retry a failed job.
      * `--timeout=3600`: Max time (seconds) a single job is allowed to run before being killed.
      * `--daemon`: Keeps the worker running in the background.
      * `user`: **Crucially, this specifies the system user that runs the worker process.** This user *must* have read/write permissions to the Laravel project's files (especially `storage/logs`). `www-data` is common for web servers, but use your specific user if you've set up different ownership.
      * `stdout_logfile`: Where to log the worker's output (useful for debugging).

#### Step 4: Load and Start Supervisor Processes

1.  **Tell Supervisor to Reread its Configuration:**

    ```bash
    sudo supervisorctl reread
    ```

    *Explanation:* This makes Supervisor aware of the new `.conf` files you just created. You should see output indicating `project_alpha_worker` and `project_beta_worker` are available.

2.  **Update Supervisor with New Processes:**

    ```bash
    sudo supervisorctl update
    ```

    *Explanation:* This loads the newly configured programs into Supervisor's active process list.

3.  **Start the Queue Workers:**

    ```bash
    sudo supervisorctl start project_alpha_worker:*
    sudo supervisorctl start project_beta_worker:*
    ```

    *Explanation:* This commands Supervisor to start the processes defined in your configuration files. The `:*` tells it to start all instances if you had `numprocs` \> 1.

4.  **Verify Worker Status:**

    ```bash
    sudo supervisorctl status
    ```

    *Explanation:* You should see output similar to this, indicating your workers are running:

    ```
    project_alpha_worker:project_alpha_worker_00 RUNNING pid 1234, uptime 0:01:30
    project_beta_worker:project_beta_worker_00 RUNNING pid 5678, uptime 0:01:25
    ```

#### Step 5: Handling Deployments

When you deploy new code to either `project_alpha` or `project_beta`, the running queue workers will still be using the old code. You need to restart them.

1.  **After deploying new code to a project (e.g., `project_alpha`):**

      * Navigate to that project's directory:
        ```bash
        cd /var/www/project_alpha
        ```
      * Execute the `queue:restart` command. This signals the worker to gracefully finish its current job and then exit. Supervisor will then automatically restart it with the new code.
        ```bash
        php artisan queue:restart
        ```
      * **Repeat for `project_beta` whenever it's deployed.**

    *Explanation:* This ensures your queue workers always run the latest version of your application code without manually stopping/starting Supervisor processes.

-----

You now have a robust and isolated queue setup for multiple Laravel applications on your VPS using the Database driver and Supervisor.