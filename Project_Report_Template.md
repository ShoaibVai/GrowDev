**GrowDev - AI-Powered Project Management Platform**

### **Submitted By**

| Student Name | Student ID |
| :---: | :---: |
| Shoaib Ibna Omar | [Student ID] |
| Mansura Yeasmin | [Student ID] |

**MINI LAB PROJECT REPORT**

This Report Presented in Partial Fulfillment of the course **CSEXXX: Subject Name in the Computer Science and Engineering Department**

### **DAFFODIL INTERNATIONAL UNIVERSITY**

**Dhaka, Bangladesh**

**December 17, 2025**

## **DECLARATION** {#declaration}

We hereby declare that this lab project has been done by us under the supervision of **Name of the course teacher**, **course teacher’s Designation**, Department of Computer Science and Engineering, Daffodil International University. We also declare that neither this project nor any part of this project has been submitted elsewhere as lab projects.

**Submitted To:**

**Course Teacher’s Name**

Designation

Department of Computer Science and Engineering Daffodil International University

**Submitted by**

|    Shoaib Ibna Omar Student ID: [ID] Dept. of CSE, DIU |  |
| ----- | ----- |
|    Mansura Yeasmin Student ID: [ID] Dept. of CSE, DIU |     |

## **COURSE & PROGRAM OUTCOME** {#course-&-program-outcome}

The following course have course outcomes as following:.

Table 1: Course Outcome Statements

| CO’s | Statements |
| :---: | :---- |
| CO1 | **Define** and **Relate** classes, objects, members of the class, and relationships among them needed for solving specific problems |
| CO2 | **Formulate** knowledge of object-oriented programming and Java in problem solving |
| CO3 | **Analyze** Unified Modeling Language (UML) models to **Present** a specific problem |
| CO4 | **Develop** solutions for real-world complex problems **applying** OOP concepts while evaluating their effectiveness based on industry standards. |

Table 2: Mapping of CO, PO, Blooms, KP and CEP

| CO | PO | Blooms | KP | CEP |
| :---: | :---- | :---: | :---: | :---: |
| CO1 | PO1 | C1, C2 | KP3 | EP1, EP3 |
| CO2 | PO2 | C2 | KP3 | EP1, EP3 |
| CO3 | PO3 | C4, A1 | KP3 | EP1, EP2 |
| CO4 | PO3 | C3, C6, A3, P3 | KP4 | EP1, EP3 |

The mapping justification of this table is provided in section **4.3.1**, **4.3.2** and **4.3.3**.

# **Table of Contents**

**Declaration**	[**i**](#declaration)

**Course & Program Outcome**	[**ii**](#course-&-program-outcome)

1. **Introduction**	[**1**](#chapter-1)

   1. Introduction	[1](#introduction)

   2. Motivation	[1](#motivation)

   3. Objectives	[1](#objectives)

   4. Feasibility Study	[1](#feasibility-study)

   5. Gap Analysis	[1](#gap-analysis)

   6. Project Outcome	[1](#project-outcome)

2. **Proposed Methodology/Architecture**	[**2**](#chapter-2)

   1. Requirement Analysis & Design Specification	[2](#requirement-analysis-&-design-specification)

      1. Overview	[2](#overview)

      2. Proposed Methodology/ System Design	[2](#proposed-methodology/-system-design)

      3. UI Design	[2](#ui-design)

   2. Overall Project Plan	[2](#overall-project-plan)

3. **Implementation and Results**	[**3**](#chapter-3)

   1. Implementation	[3](#implementation)

   2. Performance Analysis	[3](#performance-analysis)

   3. Results and Discussion	[3](#results-and-discussion)

4. **Engineering Standards and Mapping**	[**4**](#chapter-4)

   1. Impact on Society, Environment and Sustainability	[4](#impact-on-society,-environment-and-sustainability)

      1. Impact on Life	[4](#impact-on-life)

      2. Impact on Society & Environment	[4](#impact-on-society-&-environment)

      3. Ethical Aspects	[4](#ethical-aspects)

      4. Sustainability Plan	[4](#sustainability-plan)

   2. Project Management and Team Work	[4](#project-management-and-team-work)

   3. Complex Engineering Problem	[4](#complex-engineering-problem)

      1. Mapping of Program Outcome	[4](#mapping-of-program-outcome)

      2. Complex Problem Solving	[4](#complex-problem-solving)

      3. Engineering Activities	[5](#engineering-activities)

Table of Contents	Table of Contents

5. **Conclusion**	[**6**](#chapter-5)

   1. Summary	[6](#summary)

   2. Limitation	[6](#limitation)

   3. Future Work	[6](#future-work)

**References**	[**6**](#future-work)

	  
	

	  
	

**Chapter 1**

# **Introduction**

This chapter provides an overview of the GrowDev project, including its background, motivation, objectives, and expected outcomes.

1. ### **Introduction** {#introduction}

GrowDev is a modern, full-stack project management platform designed specifically for software development teams. In the fast-paced world of software development, efficient project management is crucial for success. Traditional tools often lack integration with the actual development workflow or require manual effort to translate requirements into actionable tasks. GrowDev addresses this by combining traditional project management features (Kanban boards, task tracking) with AI-powered capabilities. It leverages the Google Gemini API to automatically generate tasks from project requirements, ensuring that no detail is overlooked and that the transition from planning to execution is seamless. The platform also includes comprehensive features for SRS (Software Requirements Specification) documentation, team management, and progress tracking.

2. ### **Motivation** {#motivation}

The primary motivation behind developing GrowDev is to streamline the software development lifecycle. We observed that many teams struggle with the initial phase of breaking down high-level requirements into specific, assignable tasks. This process is often time-consuming and prone to human error. By integrating AI into the workflow, we aim to automate this critical step, allowing developers and project managers to focus on higher-value activities. Additionally, having a unified platform that handles everything from requirements gathering (SRS) to task execution and team collaboration reduces context switching and improves overall team productivity. Learning to build such a complex system also provides valuable experience in full-stack development using modern frameworks like Laravel and Vue.js.

3. ### **Objectives** {#objectives}

The key objectives of the GrowDev project are:

*   To develop a comprehensive project management system with Kanban board functionality.
*   To integrate Google Gemini API for intelligent, automated task generation based on project requirements.
*   To implement a robust SRS management system that links requirements directly to tasks.
*   To create a role-based team management system with granular permissions.
*   To provide real-time progress tracking and smart notifications for team members.
*   To build a responsive and user-friendly interface using Tailwind CSS and Blade/Vue.js.

4. ### **Feasibility Study** {#feasibility-study}

A feasibility study was conducted by analyzing existing project management tools like Jira, Trello, and Asana. While these tools are powerful, they often require third-party plugins for AI integration or lack specific features for requirement-to-task automation out of the box. GrowDev is technically feasible as it utilizes stable and widely supported technologies: Laravel for the backend, MySQL for the database, and standard web technologies for the frontend. The integration with Google Gemini API is well-documented and cost-effective for the scale of this project. The development team has the necessary skills in PHP, JavaScript, and database design to execute the project successfully.

5. ### **Gap Analysis** {#gap-analysis}

Existing solutions often treat requirements management and task management as separate silos. There is a gap in tools that seamlessly bridge the "Requirement -> Task" transition without manual intervention. Most tools require a project manager to manually create tickets from a requirements document. GrowDev fills this gap by using AI to parse requirements and suggest a breakdown of tasks, complete with estimated efforts and role assignments. This specific focus on the "AI-assisted planning phase" differentiates GrowDev from generic project management tools.

6. ### **Project Outcome** {#project-outcome}

The outcome of this project is a fully functional web-based platform, GrowDev. Users can create projects, document requirements in a structured SRS format, and use AI to generate a backlog of tasks. Teams can collaborate on these tasks using a Kanban board, track their progress, and manage their profiles and portfolios. The system demonstrates the practical application of AI in software engineering management and serves as a complete solution for small to medium-sized development teams.

**Chapter 2**

# **Proposed Methodology/Architecture**

This chapter details the technical approach, system architecture, and design specifications used to build GrowDev.

1. ### **Requirement Analysis & Design Specification** {#requirement-analysis-&-design-specification}

   1. #### **Overview** {#overview}
      The system is designed as a web-based application following the Model-View-Controller (MVC) architectural pattern. The core requirements include user authentication, project management, task tracking, and AI integration. The system handles various user roles such as Administrators, Project Managers, and Developers, each with specific permissions.

   2. #### **Proposed Methodology/ System Design** {#proposed-methodology/-system-design}
      The backend is built with **Laravel 11**, providing a robust foundation for API development, authentication (via Sanctum), and database management (Eloquent ORM). The frontend utilizes **Blade templates** enhanced with **Vue.js** components for dynamic interactivity, styled with **Tailwind CSS**. The database is **MySQL**, designed with normalized tables for Users, Projects, Tasks, and Requirements.

      **Key Architectural Components:**
      *   **Client Layer**: Web browser accessing the application via HTTP/HTTPS.
      *   **Application Layer**: Laravel framework handling routing, controllers, and business logic.
      *   **Service Layer**: Dedicated services for AI (Gemini), Notifications, and Task Management.
      *   **Data Layer**: MySQL database storing persistent data.

      *(Note: Insert System Architecture Diagram here)*

3. #### **UI Design** {#ui-design}
      The User Interface is designed with a "mobile-first" approach using Tailwind CSS. Key UI components include:
      *   **Dashboard**: An overview of active projects and pending tasks.
      *   **Kanban Board**: A drag-and-drop interface for managing task status (To Do, In Progress, Done).
      *   **SRS Editor**: A structured form for inputting functional and non-functional requirements.
      *   **AI Interaction Modal**: A clean interface for reviewing and accepting AI-generated tasks.

      *(Note: Insert UI Mockups or Screenshots of Dashboard and Kanban Board here)*

   2. ### **Overall Project Plan** {#overall-project-plan}
      The project development followed an Agile methodology with the following phases:
      1.  **Planning & Design**: Database schema design, UI prototyping, and technology selection.
      2.  **Core Development**: Setting up Laravel, implementing Authentication, and basic CRUD for Projects.
      3.  **Feature Implementation**: Developing the Kanban board, SRS module, and Team management.
      4.  **AI Integration**: Connecting to Google Gemini API and implementing the prompt engineering logic.
      5.  **Testing & Refinement**: Unit testing, bug fixing, and UI polishing.
      6.  **Documentation**: Writing technical documentation and user guides.

**Chapter 3**

# **Implementation and Results**

This chapter describes the implementation details, performance considerations, and the final results of the project.

1. ### **Implementation** {#implementation}
      The project was implemented using the following technologies and tools:
      *   **Backend**: PHP 8.2+, Laravel 11.x Framework.
      *   **Frontend**: HTML5, JavaScript (ES6+), Tailwind CSS, Alpine.js/Vue.js.
      *   **Database**: MySQL 8.0.
      *   **AI Service**: Google Gemini API (gemini-flash-latest model).
      *   **Development Tools**: VS Code, Git, Composer, NPM.

      **Key Implementation Highlights:**
      *   **AI Service**: A dedicated `PuterAIService` (wrapping Gemini) was created to handle prompt generation and response parsing. This service constructs a context-aware prompt including project details and requirements to get structured JSON output for tasks.
      *   **Kanban Board**: Implemented using JavaScript drag-and-drop libraries, updating task status via AJAX calls to the backend API.
      *   **Real-time Updates**: Laravel Echo and Pusher (or database notifications) are used to push updates to team members when tasks are assigned or completed.

   2. ### **Performance Analysis** {#performance-analysis}
      Performance optimization was a key focus during development.
      *   **Database Optimization**: Indexes were added to frequently queried columns (e.g., `project_id`, `user_id`, `status`) to speed up lookups.
      *   **Caching**: Laravel's cache system is used to store expensive query results and configuration data.
      *   **Asset Optimization**: Vite is used to bundle and minify CSS and JavaScript assets, ensuring fast load times.
      *   **Asynchronous Processing**: AI task generation and email notifications are processed in the background using Laravel Queues to prevent blocking the user interface.

   3. ### **Results and Discussion** {#results-and-discussion}
      The final system successfully meets all the defined objectives. Users can register, create projects, and generate tasks using AI with a high degree of relevance. The Kanban board provides a smooth user experience for task management.

      *(Note: Insert Screenshot of the Working Application - Home Page)*
      *(Note: Insert Screenshot of AI Task Generation Result)*
      *(Note: Insert Screenshot of Project Dashboard)*

**Chapter 4**

# **Engineering Standards and Mapping**

This chapter analyzes the project's impact on society and environment, ethical considerations, and maps the work to engineering program outcomes.

1. ### **Impact on Society, Environment and Sustainability** {#impact-on-society,-environment-and-sustainability}

   1. #### **Impact on Life** {#impact-on-life}
      GrowDev positively impacts the professional lives of software developers and project managers by reducing administrative overhead. By automating routine planning tasks, it reduces stress and burnout associated with project management, allowing individuals to focus on creative problem-solving and coding.

      2. #### **Impact on Society & Environment** {#impact-on-society-&-environment}
      While primarily a digital tool, efficient project management leads to better resource utilization. By helping teams complete projects on time and within budget, it contributes to economic efficiency. The "paperless" nature of the platform supports environmental sustainability by reducing the need for physical documentation.

      3. #### **Ethical Aspects** {#ethical-aspects}
      The project adheres to ethical standards regarding data privacy and AI usage. User data is encrypted, and the AI integration is designed to assist humans, not replace them. We ensure transparency by clearly labeling AI-generated content and requiring human review before any AI suggestion is finalized.

      4. #### **Sustainability Plan** {#sustainability-plan}
      The project is built on open-source technologies (Laravel, PHP, MySQL), ensuring long-term sustainability without heavy licensing costs. The modular architecture allows for easy updates and maintenance. Future sustainability is ensured by comprehensive documentation and a clean codebase that can be maintained by the community or future developers.

   2. ### **Project Management and Team Work** {#project-management-and-team-work}
      The project was executed using Agile methodologies. The team used Git for version control, ensuring collaborative coding and history tracking.
      **Cost Analysis:**
      *   **Development Cost**: $0 (Student Project / Open Source).
      *   **Hosting**: Estimated $5-10/month for a VPS (e.g., DigitalOcean) or free tier on platforms like Vercel/Heroku (for frontend/backend split).
      *   **AI API Costs**: Google Gemini API (Free tier for low volume, scalable pay-as-you-go).
      *   **Revenue Model**: Potential for a "Freemium" model where basic features are free, and advanced AI usage or larger team sizes require a subscription.

3. ### **Complex Engineering Problem** {#complex-engineering-problem}

   1. #### **Mapping of Program Outcome** {#mapping-of-program-outcome}

In this section, provide a mapping of the problem and provided solution with targeted Program Outcomes (PO’s).

Table 4.1: Justification of Program Outcomes

| PO’s | Justification |
| :---- | :---- |
| PO1 | **Engineering Knowledge**: Applied knowledge of mathematics (logic), science, and engineering fundamentals to build a complex software system. |
| PO2 | **Problem Analysis**: Identified the problem of inefficient task planning and formulated a solution using AI and web technologies. |
| PO3 | **Design/Development of Solutions**: Designed a full-stack architecture and developed a solution that meets specific user needs for project management. |

2. #### **Complex Problem Solving** {#complex-problem-solving}

In this section, provide a mapping with problem solving categories. For each mapping add subsections to put rationale (Use Table [4.2).](#table-4.2:-mapping-with-complex-problem-solving.) For P1, you need to put another mapping with

Chapter 4\. Engineering Standards and Mapping	4.3. Complex Engineering Problem 

Knowledge profile and rational thereof.

Table 4.2: Mapping with complex problem solving.

| EP1 Dept of Knowledge | EP2 Range of Conflicting Requirements | EP3 Depth of Analysis | EP4 Familiarity of Issues | EP5 Extent of Applicable Codes | EP6 Extent Of Stakeholder Involvement | EP7 Inter- dependence |
| ----- | :---: | ----- | ----- | ----- | ----- | ----- |
| Applied advanced web dev concepts (MVC, API) | Balanced user ease-of-use with complex AI backend logic | Analyzed AI prompt engineering and database optimization | Addressed common PM tool issues (complexity vs power) | Used standard frameworks (Laravel) and coding standards (PSR) | Considered needs of Devs, PMs, and Admins | Backend, Frontend, and AI service are tightly coupled |

3. #### **Engineering Activities** {#engineering-activities}

In this section, provide a mapping with engineering activities. For each mapping add subsections to put rationale (Use Table [4.3).](#table-4.3:-mapping-with-complex-engineering-activities.)

Table 4.3: Mapping with complex engineering activities.

| EA1 Range of resources | EA2 Level of Interaction | EA3 Innovation | EA4 Consequences for society and environment | EA5 Familiarity |
| :---: | ----- | ----- | ----- | ----- |
| Used Git, VS Code, Cloud APIs, Database tools | Interacted with team members and potential users for feedback | Integrated Generative AI for structural task planning | Positive impact on team productivity | Familiar web technologies used in a novel way |

**Chapter 5**

# **Conclusion**

This chapter summarizes the project achievements, discusses limitations, and outlines future development plans.

1. ### **Summary** {#summary}
      GrowDev successfully demonstrates the potential of combining traditional project management tools with modern AI capabilities. The platform provides a robust solution for software teams to manage their projects from requirements to delivery. By automating the task generation process, it addresses a significant pain point in the software development lifecycle. The project also served as an excellent learning opportunity for mastering full-stack development with Laravel and Vue.js.

   2. ### **Limitation** {#limitation}
      Despite its success, the current version has some limitations:
      *   **Offline Access**: The application requires an active internet connection, especially for AI features.
      *   **Mobile App**: Currently, it is a responsive web app, but a native mobile application is not yet available.
      *   **AI Context Limit**: The AI's ability to generate tasks is limited by the context window of the API; extremely large projects might need to be broken down manually first.
      *   **Integration**: Lack of integration with other tools like GitHub, Slack, or Jira.

   3. ### **Future Work** {#future-work}
      Future development plans include:
      *   **GitHub Integration**: Automatically creating GitHub issues from GrowDev tasks.
      *   **Advanced Analytics**: Adding burn-down charts and team velocity metrics.
      *   **Mobile Application**: Developing a native mobile app using Flutter or React Native.
      *   **Voice Commands**: Implementing voice-to-text for quick task creation.
      *   **Multi-language Support**: Localizing the interface for non-English speaking teams.

# **References**

\[1\] Laravel Documentation. [Online]. Available: https://laravel.com/docs/11.x
\[2\] Vue.js Documentation. [Online]. Available: https://vuejs.org/guide/introduction.html
\[3\] Google AI for Developers. "Gemini API Overview". [Online]. Available: https://ai.google.dev/docs
\[4\] Tailwind CSS Documentation. [Online]. Available: https://tailwindcss.com/docs
\[5\] Sommerville, I. (2015). *Software Engineering* (10th ed.). Pearson.