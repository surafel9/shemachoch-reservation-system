# PHP Concepts Used in Shemachoch Project

This document outlines the core PHP concepts and techniques implemented in the Shemachoch Customer and Admin panels.

## 1. Session Management

The application relies heavily on PHP sessions to maintain state across different pages.

- **`session_start()`**: Initialized at the top of almost every file to access session data.
- **`$_SESSION`**: Used for user authentication (storing user IDs), role management, and "flash messages" (temporary success/error alerts).

## 2. Database Interaction (MySQLi & PDO)

The project uses two different database extensions:

- **MySQLi**: Used in the Admin Panel for procedural and object-oriented database operations.
- **PDO (PHP Data Objects)**: Used in the Customer Panel (`db.php`). PDO is more modern and supports multiple database types, providing a consistent interface for data access.
- **Prepared Statements**: Both extensions use prepared statements to protect against **SQL Injection**.

## 3. Error Handling & Exceptions

- **Try-Catch Blocks**: Used with PDO to catch database connection errors and other exceptions (`PDOException`), preventing the script from crashing and allowing for custom error messages.
- **Custom Error Handlers**: The project includes dedicated files (`errorhandlers.php`, `errorhandling.php`) to centralize validation logic and error reporting.

## 4. Form Handling & Superglobals

PHP's superglobal arrays are used to process user input:

- **`$_POST`**: Used for sensitive data like passwords, registration details, and product updates.
- **`$_GET`**: Used for non-sensitive data, such as passing IDs for deletion or filtering.
- **`$_FILES`**: Specifically used in the Admin Panel for handling product image uploads.

## 5. Modular Architecture

To keep the code clean and DRY (Don't Repeat Yourself), the project uses:

- **`include` & `require`**: These functions pull in reusable components like `header.php`, `footer.php`, `sidebar.php`, and database configurations.

## 6. Security Best Practices

- **XSS Prevention**: Using `htmlspecialchars()` when echoing data to the browser to prevent malicious scripts from executing.
- **SQL Injection Prevention**: As mentioned, using prepared statements for all queries involving user input.
- **Input Validation**: Server-side checks (e.g., `empty()`, `isset()`) to ensure all required fields are provided before database insertion.

## 7. Control Structures

- **Conditional Logic**: `if`, `else if`, and `else` statements control the flow, such as checking if a user is an admin or if a product is out of stock.
- **Loops**: `while` loops are used to iterate through database results to dynamically generate the product grid and reservation tables.

## 8. File System Operations

- **`move_uploaded_file()`**: Used in the admin panel to move uploaded product images from temporary storage to the `uploads/` directory.
- **Unique Naming**: Using `uniqid()` to rename uploaded files to prevent overwriting existing images.

## 9. Data Types and Type Casting

- **Type Casting**: Converting strings to integers using `(int)` (e.g., `$qty = (int)$_POST["quantity"]`) to ensure mathematical operations and database queries are accurate.
- **Null Coalescing Operator**: Using `??` to provide default values for variables that might not be set in `$_POST` or `$_SESSION`.

## 10. String and Array Manipulation

- **`trim()`**: Removing whitespace from user input to ensure clean data.
- **`strlen()`**: Checking the length of strings (like passwords) for validation.
- **`fetch(PDO::FETCH_ASSOC)`**: Fetching database results as associative arrays for easy access to column names.

## 11. Mathematical Operations

- **Balance Calculations**: Performing arithmetic in PHP to check if a user has enough balance before a purchase and updating the balance accordingly.

## 12. Redirection and State

- **`header("Location: ...")`**: Used to redirect users after actions like logging in, signing out, or completing a purchase.
- **`exit()`**: Always called after a header redirection to ensure no further code is executed.

---

_Generated for the Shemachoch Project Documentation._
