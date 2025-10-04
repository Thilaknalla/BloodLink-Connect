# BloodLink-Connect
BloodLink Connect is a web-based blood donor management system built with PHP and MySQL. Users can register, verify via OTP, upload and manage donor details, while anyone can view donors without login. It also supports sharing donor info on social media, helping connect people faster and save lives.

---

## **Step 1: Requirements**

Make sure you have:

* **XAMPP** installed (Apache + MySQL)
* **Composer** installed (for PHP dependencies)
* A web browser (Chrome, Edge, Firefox)
* Optional: **Twilio account** (for SMS OTP) or email credentials (SMTP)

---

## **Step 2: Copy Project Files**

1. Download or clone your `BLOODLINKCONNECT` folder.
2. Move the entire folder to **XAMPP’s web root**:

   * Windows: `C:\xampp\htdocs\BLOODLINKCONNECT`
   * macOS/Linux: `/opt/lampp/htdocs/BLOODLINKCONNECT`

> Make sure the folder contains all the files and folders: `assets/`, `vendor/`, `JS/`, and all PHP files.

---

## **Step 3: Start XAMPP Services**

1. Open **XAMPP Control Panel**.
2. Start **Apache** and **MySQL**.
3. Ensure both show green “Running” status.

---

## **Step 4: Create Database**

1. Open **phpMyAdmin**: `http://localhost/phpmyadmin`
2. Click **Databases** → Create a new database called:

   ```
   bloodlinkconnect
   ```
3. Import or run the SQL below in phpMyAdmin → SQL tab:

```sql
CREATE TABLE users (
  ID INT AUTO_INCREMENT PRIMARY KEY,
  Username VARCHAR(100) NOT NULL,
  Password VARCHAR(255) NOT NULL,
  Mobile VARCHAR(30),
  Role ENUM('user','admin') DEFAULT 'user',
  OTP VARCHAR(10) DEFAULT NULL,
  OTP_expired DATETIME DEFAULT NULL
);


CREATE TABLE donors (
  ID INT AUTO_INCREMENT PRIMARY KEY,
  User_ID INT,
  Name VARCHAR(150) NOT NULL,
  Age INT,
  Gender ENUM('Male','Female','Other') DEFAULT 'Male',
  Blood VARCHAR(5) NOT NULL,
  Place VARCHAR(150),
  Contact VARCHAR(30),
  Uploaded_by VARCHAR(100),
  FOREIGN KEY (User_ID) REFERENCES users(ID) ON DELETE SET NULL
);

```

---

## **Step 5: Configure Database Connection**

1. Open `db.php` in a text editor.
2. Update the variables to match your XAMPP setup:

```php
<?php
$servername = "localhost";
$username = "root"; // XAMPP default
$password = "";     // XAMPP default
$dbname = "bloodlinkconnect";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```

---

## **Step 6: Install Dependencies (Composer)**

1. Open terminal/PowerShell in project root (where `composer.json` is).
2. Run:

```
composer install
```

This will install Twilio and other necessary PHP libraries in the `vendor/` folder.

---

## **Step 7: Set Up OTP**

* Open `verify_otp.php` and update either:

  * **Twilio credentials:** SID, Auth Token, From number
  * **SMTP credentials** if using email OTP

> Note: Twilio trial accounts require **verified numbers**.

---

## **Step 8: Run the Project**

1. Open browser → go to:

```
http://localhost/BLOODLINKCONNECT/index.php
```

2. You should see the **homepage** with navigation links.
3. You can now **register, login, upload donor info, and view the dashboard**.

---

## **Step 9: Using the Features**

* **Register / Verify OTP** → upload donor details.
* **Dashboard** → view total donors, recent donor, blood group summary.
* **Edit / Delete** → users can edit **only their own entries**.
* **Precautions & Blood Banks** → informational pages.
* **Social Sharing** → share donor info to social media to help others.


