## 📸 Screenshots

Below are the screenshots of the system in order of the user flow:

### 1. Landing Page

The initial entry point for all users.
![Landing Page](Screenshots/LandingPage.png)

### 2. Authentication

#### Admin Login

![Admin Login](Screenshots/AdminLogin.png)

#### Customer Login

![Customer Login](Screenshots/CustomerLogin.png)

### 3. Admin Panel

#### Admin Dashboard

![Admin Dashboard](Screenshots/AdminDashboard.png)

#### Manage Goods

![Admin Manage Goods](Screenshots/AdminManageGoods.png)

#### Reservations

![Admin Reservation](Screenshots/AdminReservation.png)

#### Reports

![Admin Report](Screenshots/AdminReport.png)

### 4. Customer Portal

#### Product View

![Customer View](Screenshots/customer.png)

#### Customer Dashboard

![Customer Dashboard](Screenshots/CustomerDashboard.png)

#### Customer Dashboard (Light Mode)

![Customer Dashboard In Light](Screenshots/CustomerDashboardInLight.png)

---

## 🛠️ Installation & Setup

1. **Clone the repository:**

   ```bash
   git clone https://github.com/surafel9/shemachoch-reservation-system.git
   ```

2. **Database Setup:**
   - Open XAMPP/WAMP and start **Apache** and **MySQL**.
   - Go to `phpMyAdmin`.
   - Create a new database named `shemachochNew_db`.
   - Import the `shemachochNew_db.sql` file provided in the root directory.

3. **Configuration:**
   - Ensure the database credentials in `ShemachochAdminPanel/db_connect.php` match your local environment.

4. **Run the Application:**
   - Move the project folder to your `htdocs` directory.
   - Access the Customer Portal: `http://localhost/Shemachoch/CustomerPortal/index.php`
   - Access the Admin Panel: `http://localhost/Shemachoch/ShemachochAdminPanel/index.php`

---

## 📂 Project Structure

- `CustomerPortal/`: Frontend for customers to browse and reserve goods.
- `ShemachochAdminPanel/`: Backend management system for admins.
- `uploads/`: Directory for stored product images.
- `Screenshots/`: Visual documentation of the project.
