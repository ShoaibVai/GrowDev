# Google Gemini AI Integration for Task Generation

This project uses [Google Gemini API](https://ai.google.dev/) for AI-powered task generation.

## Overview

The AI task generation feature uses Google's Gemini 2.0 Flash model to automatically create development tasks based on project requirements, team composition, and available roles.

## Configuration

### Environment Variables

Add the following to your `.env` file:

```env
GEMINI_API_KEY=AIzaSyCWK2g9CeDcvQThgaHdWKSIwvJRbbV-Ib8
GEMINI_PROJECT=growdev
GEMINI_PROJECT_NAME=projects/788207188016
GEMINI_PROJECT_NUMBER=788207188016
GEMINI_MODEL=gemini-flash-latest
```

### Service Configuration

Configuration is stored in `config/services.php`:

```php
'gemini' => [
    'api_key' => env('GEMINI_API_KEY'),
    'project' => env('GEMINI_PROJECT', 'growdev'),
    'project_name' => env('GEMINI_PROJECT_NAME'),
    'project_number' => env('GEMINI_PROJECT_NUMBER'),
    'model' => env('GEMINI_MODEL', 'gemini-flash-latest'),
],
```

## Implementation

### 1. Gemini AI Service (`resources/js/services/puterAI.js`)
The PuterAIService class wraps Puter.js AI chat functionality:

```javascript
import puter from 'puter';

const result = await puter.ai.chat([
    { role: 'system', content: systemPrompt },
    { role: 'user', content: userPrompt }
]);
```

### 2. Frontend Integration
The AI task generation page loads the Puter service:

```blade
@vite(['resources/js/ai-tasks.js'])
```

Then calls it from JavaScript:

```javascript
const result = await window.puterAI.generateTasks(projectContext);
```

### 3. Task Generation Flow
1. User clicks "Generate Tasks with AI"
2. Frontend collects project context (requirements, team, etc.)
3. Puter.js sends context to AI model
4. AI returns structured task list
5. Tasks are assigned to team members based on roles
6. User reviews and saves tasks to database

## Benefits

✅ **No API Key Management** - Puter handles authentication
✅ **Client-Side Processing** - Better privacy, reduced server load
✅ **Simplified Backend** - No HTTP client configuration needed
✅ **Modern Architecture** - Uses ES6 modules and Vite bundling
✅ **Better UX** - Faster response times, no backend round-trip

## Configuration

The Puter integration can be toggled in `.env`:

```env
PUTER_ENABLED=true
```

## Legacy Support

The original OpenAI backend service (`app/Services/AI/TaskGenerationService.php`) is kept for backward compatibility but is no longer used by default. The backend route still exists but can be deprecated in future versions.

## Building Assets

After any changes to the JavaScript files, rebuild assets:

```bash
npm run build        # Production build
npm run dev          # Development mode with hot reload
```

## Testing

To test the Puter.js integration:

1. Navigate to any project
2. Click "🤖 AI Task Generation" 
3. Click "🚀 Generate Tasks with AI"
4. Verify tasks are generated using Puter.js (check browser console logs)

## Troubleshooting

**Issue**: "puterAI is not defined"
- **Solution**: Run `npm run build` to rebuild assets

**Issue**: Tasks not generating
- **Solution**: Check browser console for errors, ensure Puter.js loaded correctly

**Issue**: Module import errors
- **Solution**: Clear browser cache and rebuild: `npm run build`

## Future Enhancements

- [ ] Add Puter.js error handling UI
- [ ] Support for other Puter AI models
- [ ] Caching of generated tasks
- [ ] Task generation history
- [ ] Custom AI prompts per project type

## Documentation

- Puter.js: https://docs.puter.com
- Project Wiki: [Link to your wiki]
