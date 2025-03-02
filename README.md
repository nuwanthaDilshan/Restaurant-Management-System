# Hot Meal Restaurant Management System

![Hot Meal Restaurant Management System](/images/readmeImage.png)

Hot Meal is a Restaurant Management System built using PHP, HTML, CSS, Bootstrap, and MySQL. This system helps restaurants manage orders, reservations, menu items, staff, and customer interactions efficiently.

## 📌 Features
✅ Admin Dashboard – Manage orders, menu, staff, and reservations
✅ Customer Panel – Browse menu, place orders, and make reservations
✅ Order Management – Track and update food orders
✅ Menu Management – Add, update, and delete food items
✅ Billing System – Generate and print bills for customers
✅ Authentication System – Secure login for admin and users

## 🛠️ Technologies Used
- Frontend: HTML, CSS, Bootstrap, JavaScript
- Backend: PHP (Core PHP / Laravel)
- Database: MySQL
- Version Control: Git & GitHub

## 🚀 Installation Guide

```sh
# Clone the repository
git clone https://github.com/nuwanthaDilshan/Restaurant-Management-System.git

# Navigate to the project directory
cd Restaurant-Management-System

# Import the database
- Open phpMyAdmin
- Create a database (e.g., restaurant_db)
- Import database.sql file

# Configure Database Connection
##### Edit config.php with your database credentials:
$servername = "localhost";
$username = "root";
$password = "";
$database = "restaurant_db";

# Start the server
- If using XAMPP, place the project folder in htdocs
- Start Apache & MySQL from XAMPP Control Panel
- Open your browser and go to:
http://localhost/hotmeal-restaurant 
```

```sh
# Login Details

**manager Login Details**

Email	: manager@mail.com
Password: admin

**Cashier Login Details**

Email	: cashier@mail.com
Password: cashier

**customer Login Details**

Email	: normal@mail.com
Password: normal
```
## 👨‍💻 Contributing
1. Fork the project
2. Create a new branch (feature/your-feature)
3. Commit your changes (git commit -m "Added new feature")
4. Push to the branch (git push origin feature/your-feature)
5. Create a Pull Request