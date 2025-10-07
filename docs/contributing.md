# Contributing to GrowDev

Thank you for your interest in contributing to GrowDev! This document provides guidelines and information for contributors.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Workflow](#development-workflow)
- [Coding Standards](#coding-standards)
- [Submission Guidelines](#submission-guidelines)
- [Feature Requests](#feature-requests)
- [Bug Reports](#bug-reports)

## Code of Conduct

By participating in this project, you agree to abide by our Code of Conduct:

- **Be respectful**: Treat everyone with respect and kindness
- **Be inclusive**: Welcome newcomers and help them learn
- **Be collaborative**: Work together towards common goals
- **Be professional**: Keep discussions focused and constructive

## Getting Started

### 1. Fork the Repository

1. Fork the repository on GitHub
2. Clone your fork locally:
   ```bash
   git clone https://github.com/your-username/GrowDev.git
   cd GrowDev
   ```

### 2. Set Up Development Environment

Follow the [Installation Guide](installation.md) to set up your local development environment.

### 3. Create a Branch

Create a feature branch for your changes:
```bash
git checkout -b feature/your-feature-name
# or
git checkout -b fix/issue-number
```

## Development Workflow

### Branch Naming Convention

- **Features**: `feature/feature-name`
- **Bug fixes**: `fix/issue-number` or `fix/bug-description`
- **Documentation**: `docs/update-description`
- **Refactoring**: `refactor/component-name`

### Commit Message Convention

We follow the [Conventional Commits](https://www.conventionalcommits.org/) specification:

```
type(scope): description

[optional body]

[optional footer]
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, etc.)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

**Examples:**
```bash
feat(projects): add project sharing functionality
fix(auth): resolve login redirect issue
docs(api): update authentication endpoints
style(components): format Vue components
refactor(database): optimize query performance
test(projects): add unit tests for project creation
chore(deps): update Laravel to version 10.x
```

## Coding Standards

### PHP/Laravel Standards

- Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standard
- Use Laravel naming conventions
- Write descriptive variable and method names
- Add PHPDoc comments for public methods
- Use type hints for method parameters and return types

**Example:**
```php
/**
 * Create a new project for the authenticated user.
 *
 * @param array $projectData The project data
 * @return Project The created project
 * @throws \Exception If creation fails
 */
public function createProject(array $projectData): Project
{
    $project = new Project($projectData);
    $project->owner_id = auth()->id();
    $project->save();
    
    return $project;
}
```

### Vue.js/JavaScript Standards

- Use Vue 3 Composition API
- Follow [Vue.js Style Guide](https://vuejs.org/style-guide/)
- Use TypeScript when possible
- Use ESLint configuration provided
- Write descriptive component and variable names

**Example:**
```vue
<template>
  <div class="project-card">
    <h3>{{ project.name }}</h3>
    <p>{{ project.description }}</p>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  project: {
    type: Object,
    required: true
  }
})

const isActive = computed(() => {
  return props.project.status === 'active'
})
</script>
```

### CSS/Styling Standards

- Use Tailwind CSS utility classes
- Follow mobile-first responsive design
- Create reusable component classes
- Use semantic class names

### Database/Supabase Standards

- Use descriptive table and column names
- Follow PostgreSQL naming conventions
- Include proper indexes for performance
- Add Row Level Security policies
- Document schema changes

## Testing

### Running Tests

```bash
# Run PHP tests
php artisan test

# Run JavaScript tests
npm run test

# Run all tests
npm run test:all
```

### Writing Tests

- Write unit tests for new functionality
- Include integration tests for complex features
- Test edge cases and error conditions
- Aim for good test coverage

**PHP Test Example:**
```php
public function test_user_can_create_project()
{
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
        ->post('/projects', [
            'name' => 'Test Project',
            'description' => 'A test project',
            'stage' => 'Planning'
        ]);
    
    $response->assertStatus(201);
    $this->assertDatabaseHas('projects', [
        'name' => 'Test Project',
        'owner_id' => $user->id
    ]);
}
```

## Submission Guidelines

### Pull Request Process

1. **Ensure your branch is up to date:**
   ```bash
   git fetch origin
   git rebase origin/main
   ```

2. **Run tests and linting:**
   ```bash
   composer test
   npm run test
   npm run lint
   ```

3. **Create a Pull Request:**
   - Use a descriptive title
   - Fill out the PR template
   - Reference related issues
   - Add screenshots for UI changes

### Pull Request Template

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
- [ ] Tests pass locally
- [ ] New tests added
- [ ] Manual testing completed

## Screenshots (if applicable)
Add screenshots for UI changes

## Checklist
- [ ] Code follows style guidelines
- [ ] Self-review completed
- [ ] Comments added for complex code
- [ ] Documentation updated
```

### Review Process

1. **Automated Checks**: All tests and linting must pass
2. **Code Review**: At least one maintainer must approve
3. **Testing**: Feature must be manually tested
4. **Documentation**: Update docs if necessary

## Feature Requests

### Before Submitting

1. Check if feature already exists
2. Search existing feature requests
3. Consider if it fits project goals

### Feature Request Template

```markdown
## Problem Description
What problem does this solve?

## Proposed Solution
How should this feature work?

## Alternatives Considered
What other solutions did you consider?

## Additional Context
Any additional information or mockups
```

## Bug Reports

### Before Submitting

1. Check if bug already reported
2. Try to reproduce the issue
3. Test on latest version

### Bug Report Template

```markdown
## Bug Description
Clear description of the bug

## Steps to Reproduce
1. Go to '...'
2. Click on '...'
3. See error

## Expected Behavior
What should happen?

## Actual Behavior
What actually happens?

## Environment
- OS: [e.g., Windows 11]
- Browser: [e.g., Chrome 91]
- PHP Version: [e.g., 8.3]
- Laravel Version: [e.g., 10.x]

## Additional Context
Screenshots, logs, etc.
```

## Development Tips

### Useful Commands

```bash
# Laravel commands
php artisan make:controller ControllerName
php artisan make:model ModelName
php artisan make:migration create_table_name
php artisan route:list
php artisan tinker

# Frontend commands
npm run dev          # Development build with watcher
npm run build        # Production build
npm run lint         # Run ESLint
npm run lint:fix     # Fix ESLint issues

# Testing commands
php artisan test --filter=TestName
npm run test -- --watch
```

### Debugging

- Use Laravel Telescope for request debugging
- Use Vue DevTools for frontend debugging
- Check browser console for JavaScript errors
- Use `dd()` and `dump()` for PHP debugging

### Performance

- Use eager loading to prevent N+1 queries
- Optimize images and assets
- Use caching where appropriate
- Monitor database query performance

## Getting Help

- **Documentation**: Check existing docs first
- **Issues**: Search GitHub issues
- **Discussions**: Use GitHub Discussions for questions
- **Code Review**: Ask for help in PR comments

## Recognition

Contributors will be recognized in:
- README.md contributors section
- Release notes for significant contributions
- Special thanks for major features

Thank you for contributing to GrowDev! 🚀