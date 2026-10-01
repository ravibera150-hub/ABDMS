# Crime Record Management System

A web-based Crime Record Management System developed as an Advanced Database Management System (ADBMS) project.

The system helps authorized police officers manage FIRs, criminal records, evidence, and investigation reports through a centralized database.

## Features

- Police officer registration and login
- Secure password hashing
- Forgot password and password reset
- Dashboard with record statistics
- FIR registration
- FIR search, edit and delete
- Criminal record management
- Criminal search, edit and delete
- Link criminals with FIRs
- Evidence upload and management
- Investigation report management
- Search functionality
- Session-based authentication
- Database relationships using foreign keys
- File upload and deletion handling

## Technology Stack

### Frontend

- HTML5
- CSS3

### Backend

- PHP

### Database

- MySQL / MariaDB

### Development Environment

- XAMPP
- Apache
- phpMyAdmin
- Git & GitHub

## Database

Database name:

`crime_record_db`

Main tables:

- `police`
- `crime_type`
- `fir`
- `criminal`
- `fir_criminal`
- `evidence`
- `report`
- `password_reset`

## System Modules

### 1. Police Authentication

Police officers can register and log in using their registered credentials.

Passwords are stored using secure password hashing.

### 2. FIR Management

Authorized officers can:

- Register FIRs
- View FIR records
- Search FIRs
- Edit FIR details
- Update FIR status
- Delete FIRs

### 3. Criminal Management

The system provides functionality to:

- Add criminal records
- View criminal records
- Search criminals
- Edit criminal information
- Delete criminal records

### 4. FIR-Criminal Relationship

A criminal can be associated with an FIR through the `fir_criminal` junction table.

This supports a many-to-many relationship between FIRs and criminal records.

### 5. Evidence Management

Officers can upload and manage evidence files associated with FIRs.

Supported file formats include:

- JPG
- PNG
- GIF
- PDF
- TXT

Maximum file size: 5 MB.

### 6. Report Management

Officers can create and manage investigation reports associated with FIRs.

Report types include:

- Investigation Report
- Evidence Report
- Progress Report
- Final Report

## Project Structure

```text
ADBMS/
│
├── backend/
│   ├── db.php
│   ├── login.php
│   ├── logout.php
│   ├── register.php
│   ├── fir_register.php
│   ├── fir_edit.php
│   ├── fir_delete.php
│   ├── criminal_register.php
│   ├── criminal_edit.php
│   ├── criminal_delete.php
│   ├── link_criminal.php
│   ├── evidence_add.php
│   ├── evidence_edit.php
│   ├── evidence_delete.php
│   ├── report_add.php
│   ├── report_edit.php
│   ├── report_delete.php
│   ├── forgot_password.php
│   └── reset_password.php
│
├── uploads/
│   └── evidence/
│
├── index.html
├── register.php
├── dashboard.php
├── fir_register.php
├── fir_records.php
├── fir_edit.php
├── criminal_register.php
├── criminal_records.php
├── criminal_edit.php
├── link_criminal.php
├── evidence_add.php
├── evidence_records.php
├── evidence_edit.php
├── report_add.php
├── report_records.php
├── report_edit.php
├── forgot_password.php
├── reset_password.php
├── dashboard.css
├── records.css
├── auth.css
├── style.css
└── README.md
```

## Installation

1. Install XAMPP.

2. Start:
   - Apache
   - MySQL

3. Place the project inside:

```text
C:\xampp\htdocs\ADBMS
```

4. Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

5. Create the database:

```text
crime_record_db
```

6. Create/import the required database tables.

7. Open the project:

```text
http://localhost/ADBMS/
```

## Security

The system includes:

- Prepared SQL statements
- Password hashing using PHP `password_hash()`
- Password verification using `password_verify()`
- Session-based authentication
- Session ID regeneration after login
- Input validation
- Output escaping
- File type and size validation
- Protected database operations

## Project Purpose

This project demonstrates practical implementation of database concepts such as:

- Relational database design
- Primary and foreign keys
- One-to-many relationships
- Many-to-many relationships
- CRUD operations
- SQL queries
- Database normalization concepts
- Transaction handling
- Data validation
- Web-based database application development

## Future Scope

Possible future improvements include:

- Role-based access control
- Advanced reporting and analytics
- Email-based password reset
- Audit logs
- Police station-wise reports
- Advanced search and filtering
- Cloud deployment
- Mobile application integration

## Author

Developed as an academic project for Advanced Database Management System (ADBMS).

**Project:** Crime Record Management System

**Technology:** PHP, MySQL/MariaDB, HTML, CSS