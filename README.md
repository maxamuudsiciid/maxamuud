# Blood Donation Management System

A comprehensive, responsive, and robust web application built with **Laravel 12** and **Bootstrap 5** to automate and streamline blood bank operations, from donor registration to blood distribution.

## Key Features
- **Role-Based Access Control**: Secure logins for Admins, Staff, Donors, and Hospitals.
- **Donor Management**: Register donors, track medical history, and record donations.
- **Real-Time Inventory**: Automated tracking of blood stock levels across all blood groups.
- **Hospital Blood Requests**: Hospitals can submit blood requests, specifying urgency levels.
- **Distribution Module**: Automated deduction of inventory upon request fulfillment.
- **Reports Module**: Generate and print detailed reports for Donors, Inventory, Requests, and Distributions.
- **Smart Notifications**: Built-in alerts for low blood stock and request status updates.
- **Modern UI**: Dark/Light mode toggle, DataTables for advanced sorting/searching, and SweetAlert2 for beautiful popups.

## Technologies Used
- Backend: **Laravel 12 (PHP 8.2+)**
- Database: **MySQL** via Eloquent ORM
- Frontend: **Bootstrap 5, FontAwesome 6, DataTables, SweetAlert2**

---

## Installation & Setup

1. **Clone or Extract the Project**
2. **Configure Environment**
   Update your `.env` file to connect to your local MySQL database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=blood_donation_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. **Run Migrations and Seeders**
   This will build the database schema and populate it with sample data:
   ```bash
   php artisan migrate:fresh --seed
   ```
4. **Start the Application**
   ```bash
   php artisan serve
   ```
   Navigate to `http://localhost:8000` or via XAMPP `http://localhost/Blood%20Donation%20Management/public/`.

---

## Default Test Accounts
- **Admin**: `admin@bloodbank.com` / `password`
- **Staff**: `staff@bloodbank.com` / `password`
- **Hospital**: `contact@cityhospital.com` / `password`
- **Donor**: `john@donor.com` / `password`

---

## System Diagrams

### 1. Use Case Diagram
Visualizes how different user roles interact with the system's features.

```mermaid
flowchart LR
    %% Actors
    Admin(["🧑‍💼 Admin / Staff"])
    Donor(["🩸 Blood Donor"])
    Hospital(["🏥 Hospital"])

    %% System
    subgraph "Blood Donation Management System"
        UC1("Login / Register")
        UC2("Manage Users & Donors")
        UC3("Record Blood Donations")
        UC4("Monitor Live Inventory")
        UC5("Submit Blood Request")
        UC6("Approve / Reject Requests")
        UC7("Dispatch & Distribute Blood")
        UC8("Generate & Print Reports")
    end

    %% Connections
    Admin --> UC1
    Donor --> UC1
    Hospital --> UC1

    Admin --> UC2
    Admin --> UC3
    Admin --> UC4
    Admin --> UC6
    Admin --> UC7
    Admin --> UC8

    Hospital --> UC5
```

### 2. Entity Relationship Diagram (ERD)
Maps the database architecture and relationships between core models.

```mermaid
erDiagram
    USER ||--o| DONOR : "has profile"
    USER ||--o| HOSPITAL : "has profile"
    
    DONOR ||--o{ BLOOD_COLLECTION : "makes"
    
    HOSPITAL ||--o{ BLOOD_REQUEST : "submits"
    HOSPITAL ||--o{ DISTRIBUTION : "receives"
    
    BLOOD_REQUEST ||--o| DISTRIBUTION : "fulfilled by"
    
    BLOOD_COLLECTION }o--o| BLOOD_INVENTORY : "increases (if Safe)"
    DISTRIBUTION }o--o| BLOOD_INVENTORY : "decreases"

    USER {
        int id PK
        string name
        string email
        string role "admin, staff, hospital, donor"
    }
    
    DONOR {
        int id PK
        int user_id FK
        string blood_group
        string status
    }
    
    HOSPITAL {
        int id PK
        int user_id FK
        string hospital_name
        string contact_person
    }
    
    BLOOD_COLLECTION {
        int id PK
        int donor_id FK
        string blood_group
        int quantity
        string screening_result
    }
    
    BLOOD_REQUEST {
        int id PK
        int hospital_id FK
        string blood_group
        int quantity
        string status
    }
    
    BLOOD_INVENTORY {
        int id PK
        string blood_group
        int quantity
    }
```

### 3. Workflow Flowchart (Request & Distribution Lifecycle)
Illustrates the step-by-step logic when a hospital requests blood.

```mermaid
flowchart TD
    A([Start]) --> B[Hospital submits Blood Request]
    B --> C[Admin reviews Request]
    C --> D{Is blood in stock?}
    
    D -- Yes --> E[Admin Approves Request]
    D -- No --> F[Admin Rejects / Pending]
    
    E --> G[Create Distribution Record]
    G --> H[Deduct amount from Blood Inventory]
    
    H --> I{Is Stock < 1000ml?}
    I -- Yes --> J[Trigger 'Low Stock' Notification]
    I -- No --> K
    
    J --> K[Mark Request as Fulfilled]
    F --> L([Wait for new Donations])
    K --> M([End Process])
```
