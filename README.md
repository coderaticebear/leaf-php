# 📦 PHP Microservices (Native PHP)

A lightweight microservices architecture built using **native PHP (no frameworks)**.  
This project is designed for learning how backend systems work internally—routing, request handling, and service separation.

---

## 🚀 Project Overview

Each service is an independent PHP application with:

- A single entry point (`index.php`)
- A simple Router
- A Controller for handling endpoints
- Shared core utilities (Request, Response, Core)

---


## ⚙️ Features

- Custom routing system (GET, POST)
- JSON request/response handling
- Singleton-based router
- Minimal and modular design
- No external frameworks

---

## 🧠 Architecture Flow

Request → index.php → Router → Controller → Response


---

## 🛠️ Setup Instructions

### 1. Install dependencies

composer install


### 2. Generate autoload files

composer dump-autoload



---

## ▶️ Run a Service

Navigate to a service folder:


cd user-service
php -S localhost:8000


## 🎯 Goals of This Project

Understand backend fundamentals without frameworks
Learn microservice architecture basics
Build a custom lightweight PHP framework
Prepare for scaling into Docker & Kubernetes environments