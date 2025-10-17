# 🌴 SisawitV2 - Palm Oil Productivity Monitoring System (Version 2)

## 📚 Table of Contents

- [Project Overview](#project-overview)
- [Problem Statement](#project-statement)
- [Objectives](#objectives)
- [System Architecture](#system-architecture)
- [Tech Stack](#tech-stack)
- [How It Works](#how-it-works)
- [Installation](#installation)
- [Testing Summary](#testing-summary)
- [Conclusion](#conclusion)

## Project Overview

**SisawitV2** is a web-based system that implements **Business Intelligence (BI)** to monitor the productivity of palm oil plantations.
It integrates harvest data collected through the SawitKu mobile app, processes it via an **ETL (Extract, Transform, Load)** pipeline using **Pentaho Data Integration (PDI)**, and visualizes the results through an interactive web dashboard.

The system is developed using the **CodeIgniter 3** framework, with **MySQL** as the database and **Docker** for simplified deployment and environment management.

## Problem Statement
Based on interviews with palm oil farmers and plantation owners located in Riau, Indonesia, the previous monitoring system did not fully support data-driven decision-making in the field.

**Key issues identified**:
- The previous dashboard did not display recent harvest trends, which are essential for monitoring at least three consecutive harvests (within 1–2 weeks) to detect productivity changes.
- Several existing charts were irrelevant to productivity analysis and required redesign.
- Fertilizer stock information was not visualized, even though it is crucial for anticipating shortages caused by delayed deliveries.

These findings led to the development of a new Business Intelligence (BI) dashboard in SisawitV2, designed to visualize comprehensive harvest performance by week, month, and plantation block to help users understand productivity patterns and make better operational decisions.


## Objectives
To develop a Business Intelligence (BI) dashboard that comprehensively displays and analyzes palm plantation data to support better operational and strategic decisions.

**Key Features**:
- Visualize harvest results by week, month, and plantation block.
- Display fertilizer stock and plantation data.
- Visualize harvest yield predictions generated from an external deep learning model (developed in a separate research project).

**Main Benefits**:
- Enable users to analyze productivity trends.
- Provide insight into each plantation’s contribution to total yield.
- Help monitor fertilizer stock levels and plantation data efficiently.
- Present yield predictions as a reference to support planning and decision-making related to future harvesting and resource allocation.

## System Architecture
SisawitV2 consists of four integrated components:
<img width="848" height="462" alt="image" src="https://github.com/user-attachments/assets/ca4d5f87-7189-494c-a022-fbebcaa0b2ca" />
- SawitKu (Mobile App): Serves as the input platform for field operational data, including planting, fertilizing, and harvesting activities.
- Pentaho Data Integration (ETL): Performs the Extract, Transform, and Load (ETL) process to move and structure data according to the defined data warehouse schema.
  <img width="1738" height="547" alt="Screenshot 2025-10-17 163000" src="https://github.com/user-attachments/assets/3822aac0-03a2-46bc-aa79-44d4b37391e7" />
- MySQL Data Warehouse: Acts as the repository that stores integrated and structured plantation data for analysis.
  <img width="824" height="740" alt="Fact Constellation drawio (1)" src="https://github.com/user-attachments/assets/2d1d1693-271e-44e2-9ee6-b9c2ed6e2a83" />
- SisawitV2 Dashboard (Web App): Visualizes harvest results and yield predictions through an interactive BI dashboard. Additional plantation data such as planting and fertilizing records are accessible through dedicated table views in the side menu.

## Tech Stack
- Frontend: PHP 8.0
- Framework: CodeIgniter 3
- Database: MySQL 5.7
- ETL Tool: Pentaho Data Integration (PDI)
- Containerization: Docker


## How It Works
- Access the system at: http://160.187.144.173/
- Login using:
    - Email: pemilik1@example.com
    - Password: password
- **Main Dashboard (Harvest Data)**
  <img width="1578" height="853" alt="Screenshot 2025-08-29 095419" src="https://github.com/user-attachments/assets/05ee70f8-405e-4468-97c5-3266031aab2c" />
- **Prediction Dashboard (Forecasted Yield)**
  <img width="1441" height="828" alt="Screenshot 2025-08-29 095459" src="https://github.com/user-attachments/assets/6f851e79-313b-465b-8035-37b0d47365a3" />

## Installation
#### 1. Clone Repository

```bash
git clone https://github.com/kellychan3/sisawitv2.git
cd sisawitv2
```

#### 2. Setup Environment
```bash
cp .env.example .env
```
Contact Kelly to obtain the .env file.

#### 3. Prepare Database Initialization
```bash
mkdir -p docker/mysql-init
```
Contact Kelly for the required *.sql files.

#### 4. Install PHP Dependencies
```bash
composer install
```

#### 5. Download PDI
Download [pdi.zip](https://drive.google.com/file/d/1L6jSetbVLe0GjPMxcAAZSqimWy_K5nVh/view?usp=sharing) and extract it into the `pentaho/` folder.

#### 6. Install & Run Docker
```bash
docker compose down -v
docker compose up --build
```

#### 7. Access the Application on your Local Computer
Open your browser and go to: [http://localhost:8081](http://localhost:8081)


## Testing Summary
Testing was conducted through two main phases to evaluate functionalitya and usability.
- **User Acceptance Test (UAT)**: All functional requirements were met successfully, with a 100% success rate across all user roles and testing scenarios. The system operated smoothly without major issues, including core features such as harvest visualization, fertilizer stock monitoring, and predictive yield dashboards.

- **Usability Test**: The dashboard achieved an average System Usability Scale (SUS) score of 90%. Users rated the system as easy to use, intuitive, and helpful for monitoring and analysis, confirming that the dashboard effectively supports real-world decision-making needs.

## Conclusion
SisawitV2 successfully integrates palm plantation operational data into a comprehensive Business Intelligence dashboard that supports data-driven decision-making.
The system enables users to:
- Monitor and analyze harvest productivity trends in real time.
- Track fertilizer stock and field activities effectively.
- Visualize predictive yield data to plan future harvesting and resource allocation.

