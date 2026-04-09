# 🎉 Eventify – Event Management Web Application

A full-stack PHP web application for creating, managing, and registering for events.  
Built from scratch using a custom MVC-like architecture with a Front Controller, Router, and Presenter pattern.

## 🚀 Overview

Eventify allows users to:
- Create and manage events
- Add and edit workshops
- Register for events with workshop selection
- Manage their profile and registrations


## ✨ Features

### 👤 Authentication
- Register with name and email
- Login via email
- Session-based authentication
- Secure logout

### 📅 Event Management
- Create new events
- Edit events
- Delete events
- View all events
- View detailed event pages

### 🧩 Workshops
- Add multiple workshops per event
- Dynamic workshop input (JavaScript)

### ✅ Registration System
- Register for events
- Select workshops
- Cancel registration
- View "My Events"

### ⚙️ User Settings
- Update username
- Delete account

## 🏗️ Architecture

The application follows a Front Controller + MVP (Model-View-Presenter) architecture.

## 🔐 Security

- CSRF protection for all POST requests
- Input validation
- Output escaping to prevent XSS

## 📷 Demo

![Landing Page Screenshot](screenshots/Screenshot%202026-04-09%20131312.png)
![All Events Page Screenshot](screenshots/Screenshot%202026-04-09%20131341.png)
![Login Page Screenshot](screenshots/Screenshot%202026-04-09%20131410.png)
![Register Page Screenshot](screenshots/Screenshot%202026-04-09%20131425.png)


## ⚙️ Installation

1. Clone repository
2. Configure BASE_PATH in index.php
3. Run database/schema.sql
4. Configure DB connection

## 🧪 Technologies

- PHP
- MySQL
- JavaScript
- HTML/CSS

## 📁 Structure

app/, assets/, data/, index.php