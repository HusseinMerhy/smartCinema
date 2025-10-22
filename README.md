

Welcome to SmartCinema, a complete cinema booking web application. This project is a decoupled system featuring a **Vanilla JavaScript frontend** and a **PHP backend API**.

It allows users to browse movies, view showtimes, book seats, and manage their accounts. It also includes a separate admin panel for managing movies and showtimes.

## Features

  * **User Features:**
      * View "Now Playing" and "Coming Soon" movies.
      * View detailed movie information (cast, trailers, ratings).
      * Browse showtimes for each movie.
      * Interactive, real-time seating chart for booking.
      * User registration and login.
      * Account page to view and cancel past/upcoming bookings.
  * **Admin Features:**
      * CRUD (Create, Read, Update, Delete) functionality for **Movies**.
      * CRUD functionality for **Showtimes**.
      * View all halls.

## Project Structure

The project is split into two main parts:

  * `/cinema-client`: The user-facing frontend, built with HTML, CSS, and JavaScript. This is what the user sees in their browser.
  * `/cinema-server`: The backend API, built with PHP. It handles all business logic and database interactions, sending data to the client as JSON.

-----

## Backend Setup (Required)

You must set up the server and database for the application to work.

### Requirements

  * A local server environment like **XAMPP**, **WAMP**, or **MAMP**.
  * This provides **Apache** (web server), **MySQL** (database), and **PHP**.

### Step 1: Database Setup

1.  **Start Apache and MySQL** in your XAMPP/WAMP control panel.
2.  Open your database admin tool (e.g., `http://localhost/phpmyadmin`).
3.  Create a new database. A good name is `smartcinema`.
4.  Open the `smartCinema-main/cinema-server/connection/db.php` file in your code editor.
5.  Change the `$database` variable to match the name you just created (e.g., `smartcinema`).
6.  Update the `$username` and `$password` variables to match your MySQL setup (XAMPP default is usually `root` and an empty password `''`).

### Step 2: Create Database Tables

This project includes migration scripts to automatically build all the required tables.

1.  In your web browser, navigate to the `run_migrations.php` script inside your `cinema-server` folder.
2.  The URL will look something like this:
    `http://localhost/smartCinema/cinema-server/migrations/run_migrations.php`
    *(This path depends on where you placed the project folder).*
3.  You should see a success message in your browser. This has now created all the tables (like `users`, `movies`, `bookings`, etc.).

### Step 3: (Optional) Add Sample Data

To add sample movies, showtimes, and halls:

1.  In your web browser, navigate to the `run-seeds.php` script inside your `cinema-server/seeds/` folder.
2.  The URL will be similar to:
    `http://localhost/smartCinema/cinema-server/seeds/run-seeds.php`
3.  This will populate your database with sample data so the website isn't empty.

-----

## Frontend Setup (Required)

This is the **most important step** to make the client and server talk to each other and fix 404 errors.

### Step 1: Run the Frontend

You can run the frontend in two ways:

1.  **File System:** Simply open the `smartCinema-main/cinema-client/index.html` file directly in your browser.
2.  **Local Server (Recommended):** Place the `cinema-client` folder inside your `htdocs` (or `www`) directory as well.

### Step 2: Link Client to Server (The 404 Fix)

You must tell the frontend the correct address of your PHP backend.

1.  Open `smartCinema-main/cinema-client/scripts/apiService.js` in your code editor.
2.  Find this line of code:
    ```javascript
    const API_BASE_URL = 'http://localhost/smartCinema/cinema-server/api'; 
    ```
3.  **You must change this URL** to match the path to *your* `cinema-server/api` folder on *your* local server.

**Examples:**

  * If you put the `smartCinema-main` folder directly in `htdocs`, your path might be:
    `http://localhost/smartCinema-main/cinema-server/api`
  * If you just put the `cinema-server` folder in `htdocs`, your path will be:
    `http://localhost/cinema-server/api`
  * If you put the project in a folder named `MyProject`, your path might be:
    `http://localhost/MyProject/cinema-server/api`

<!-- end list -->

4.  Save the file. The website should now load all movies correctly.

-----

## How to Use the Admin Panel

This project includes a separate admin panel to manage movies and showtimes.

1.  **Access:** Open `smartCinema-main/cinema-client/admin/index.html` in your browser.
2.  **Create an Admin User:**
      * There is no UI to create an admin. You must do it manually in the database.
      * First, [register a normal user](https://www.google.com/search?q=http://localhost/smartCinema/cinema-client/pages/register.html) on the main site.
      * Go to `phpmyadmin` and open your `users` table.
      * Find the user you just created.
      * Change the value in their `role` column from `'customer'` to `'admin'`.
      * Save the change.
3.  **Log In:** You can now log in on the admin page using that user's credentials. You will need their **User ID** (from the `id` column in the `users` table) and their password.
