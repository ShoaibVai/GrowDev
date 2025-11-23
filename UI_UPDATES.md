# UI/UX Enhancements — Project Management & Documentation

This file documents the UI/UX updates implemented on November 23, 2025.

## Changes

### Shared Controls Component
- Added `resources/views/components/list-controls.blade.php`.
- This component provides:
  - Search input.
  - Sort dropdown (Latest, Oldest, Name A-Z/Z-A).
  - Grid/List view toggle.
  - Extra filters support via `extraFilters` property.
  - Optional CTA slot for action buttons (Create, etc.).

### Projects
- Index: replaced ad-hoc search with `list-controls` and added Team filter.
- Index: grid/list views with a compact table list view for large lists.
- Show: added task filters (search, status, assignee) with `list-controls`. Tasks list can be filtered.

### Dashboard (new)

- Created `DashboardController` to supply comprehensive dashboard data: project counts, active/completed counts, tasks assigned, upcoming tasks, teams, recent SRS/SDD.
- Reworked `resources/views/dashboard.blade.php` with a modern layout:
  - Top stats: quick overview (Projects, Active Projects, Open Tasks, Teams)
  - Main content: recent projects (grid cards), tasks assigned to the user (table)
  - Sidebar: upcoming tasks, quick actions, recent documents, and teams list
- Added reusable components: `dashboard-stat` and `recent-task`.

### Notes
- This redesign prioritizes quick access to recent work and actionable items.
- Future improvements: integrate a calendar view, add drag-and-drop for task ordering, and persist view preferences.

### Documentation (SRS/SDD)
- Index: replaced search UI with `list-controls`, added sorting and view toggle.
- Index: added list/table view option and improved empty-state visual.

### Controller updates
- `ProjectController::index` supports filtering by `team_id` and sorting.
- `ProjectController::show` supports filtering tasks by `q`, `status`, `assigned_to` and returns filtered `tasks` and `members`.
- `DocumentationController::indexSrs` and `indexSdd` support sorting by `sort` query param.

## Tests/Validation
- Views successfully compiled with `php artisan view:cache`.
- Routes remain unchanged except for query params usage in GETs.
- Some integration tests might need DB for full verification (migration step failed during earlier runs due to MySQL not running).

## Next Steps
- Add client-side enhancements to keep grid/list state (localStorage) or remember per user.
- Add pagination and sorting UI to Tasks table for projects with many tasks.
- Create additional documentation filters (template type, status) for the documentation list.

---
If you want, I can continue and convert more partials into class-based components and improve accessibility further.
