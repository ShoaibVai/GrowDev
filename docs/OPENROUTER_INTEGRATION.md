# OpenRouter AI Integration Guide

GrowDev now uses OpenRouter API for AI-powered task generation instead of Google Gemini.

## Why OpenRouter?

OpenRouter provides access to **200+ AI models** through a single unified API:

- **OpenAI**: GPT-4, GPT-3.5-turbo
- **Anthropic**: Claude 3 (Opus, Sonnet, Haiku)
- **Meta**: Llama 2 (70B, 13B, 7B)
- **Mistral**: Mistral 7B, Mistral Medium
- **And 150+ more models...**

**Benefits:**
- ✅ Single API for multiple models
- ✅ Fallback support (automatic model failover)
- ✅ Transparent pricing
- ✅ No rate limiting concerns
- ✅ Better performance and reliability
- ✅ Easy to switch between models

## Getting Started

### 1. Create OpenRouter Account

1. Go to https://openrouter.ai
2. Sign up with email or GitHub
3. Verify your email

### 2. Get Your API Key

1. Visit https://openrouter.ai/keys
2. Click "Create new key"
3. Copy the API key
4. **Keep it safe** - don't share it publicly

### 3. Add Credits

OpenRouter uses a credit system (pre-paid):

1. Go to https://openrouter.ai/account/billing/overview
2. Click "Add Credits"
3. Choose amount ($5, $10, $25, $50, etc.)
4. Complete payment
5. Check pricing at https://openrouter.ai/docs#models

**Pricing Examples** (as of May 2026):
- GPT-3.5-turbo: ~$0.001-0.002 per 1K tokens
- Claude 3 Haiku: ~$0.00025-0.001 per 1K tokens
- Llama 2 70B: ~$0.0007-0.001 per 1K tokens

## Configuration

### Local Development

Create or update `.env.local`:

```bash
# OpenRouter Configuration
OPENROUTER_API_KEY=sk-or-v1-xxxxx  # Your OpenRouter API key
OPENROUTER_MODEL=openai/gpt-3.5-turbo  # Default model

# Frontend environment
VITE_OPENROUTER_API_KEY=sk-or-v1-xxxxx
VITE_OPENROUTER_MODEL=openai/gpt-3.5-turbo
VITE_API_URL=http://localhost:8000
```

### Production (Vercel)

Set environment variables in Vercel project:

```
VITE_OPENROUTER_API_KEY = sk-or-v1-xxxxx
VITE_OPENROUTER_MODEL = openai/gpt-3.5-turbo
```

## Model Selection

### Recommended Models

| Model | Speed | Cost | Quality | Use Case |
|-------|-------|------|---------|----------|
| `openai/gpt-3.5-turbo` | ⚡⚡⚡ | $ | Good | Default, fastest |
| `openai/gpt-4-turbo` | ⚡⚡ | $$ | Best | Complex tasks |
| `anthropic/claude-3-haiku` | ⚡⚡⚡ | $ | Good | Budget-friendly |
| `anthropic/claude-3-sonnet` | ⚡⚡ | $$ | Excellent | Best balance |
| `meta-llama/llama-2-70b-chat` | ⚡⚡ | $ | Good | Open source |

### Change Model

**Locally:**
```bash
# Set in .env.local
VITE_OPENROUTER_MODEL=anthropic/claude-3-sonnet
```

**Vercel:**
1. Go to Vercel dashboard
2. Project → Settings → Environment Variables
3. Edit `VITE_OPENROUTER_MODEL`
4. Redeploy

### List All Models

```bash
# Check available models
curl https://openrouter.ai/api/v1/models \
  -H "Authorization: Bearer sk-or-v1-xxxxx"
```

Or visit: https://openrouter.ai/docs#models

## Usage

### In Frontend (JavaScript)

The AI service is automatically loaded:

```javascript
// Available globally as window.openrouterAI
const result = await window.openrouterAI.generateTasks({
    project: { name: 'My Project', description: 'Project details...' },
    team: [...],
    functional_requirements: [...],
    non_functional_requirements: [...],
    system_roles: [...]
});

console.log(result);
// Returns:
// {
//   success: true,
//   tasks: [...],
//   raw_response: "...",
//   model_used: "openai/gpt-3.5-turbo"
// }
```

### Response Format

```javascript
{
    success: true,
    tasks: [
        {
            index: 0,
            title: "Setup Project Database",
            description: "Create and initialize the PostgreSQL database...",
            priority: "High",
            estimated_hours: 4,
            required_role: "Full Stack Developer",
            requirement_type: "functional",
            requirement_id: 1,
            dependencies: []
        },
        // ... more tasks
    ],
    raw_response: "Full AI response text",
    model_used: "openai/gpt-3.5-turbo"
}
```

## Troubleshooting

### "Invalid API Key"

```
Solution: 
1. Check your API key in Vercel environment variables
2. Ensure key starts with "sk-or-v1-"
3. Get new key from https://openrouter.ai/keys
```

### "Empty response from OpenRouter API"

```
Solution:
1. Ensure you have credits available
2. Check OpenRouter account: https://openrouter.ai/account/billing/overview
3. Try a different model if current one is rate limited
```

### "Rate limit exceeded"

```
Solution:
1. OpenRouter has generous rate limits
2. Wait a few seconds before retrying
3. Consider upgrading to higher-tier model
4. Check usage at https://openrouter.ai/account/billing/limits
```

### Model too slow

```
Solution:
Switch to faster model in .env:
VITE_OPENROUTER_MODEL=openai/gpt-3.5-turbo  # Fastest
VITE_OPENROUTER_MODEL=anthropic/claude-3-haiku  # Fast & cheap
```

### Model too expensive

```
Solution:
Switch to cheaper model:
VITE_OPENROUTER_MODEL=anthropic/claude-3-haiku  # Cheapest
VITE_OPENROUTER_MODEL=meta-llama/llama-2-70b-chat  # Budget-friendly
```

## Monitoring & Analytics

### Check Usage

1. Go to https://openrouter.ai/account/billing/overview
2. View real-time usage statistics
3. Monitor credit balance

### Cost Tracking

```javascript
// Check model info
fetch('https://openrouter.ai/api/v1/models')
  .then(r => r.json())
  .then(models => {
    const model = models.data.find(m => m.id === 'openai/gpt-3.5-turbo');
    console.log('Pricing:', model.pricing);
    // pricing: { prompt: '0.0005', completion: '0.0015' }
  });
```

## API Reference

### OpenRouter Documentation

- **Main Docs:** https://openrouter.ai/docs
- **Models List:** https://openrouter.ai/docs#models
- **API Key Management:** https://openrouter.ai/keys
- **Billing & Credits:** https://openrouter.ai/account/billing/overview
- **Status:** https://status.openrouter.ai/

### OpenRouter Request Format

```javascript
fetch('https://openrouter.ai/api/v1/chat/completions', {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${apiKey}`,
    'Content-Type': 'application/json',
    'HTTP-Referer': window.location.origin,
    'X-Title': 'GrowDev'
  },
  body: JSON.stringify({
    model: 'openai/gpt-3.5-turbo',
    messages: [
      { role: 'system', content: '...' },
      { role: 'user', content: '...' }
    ],
    temperature: 0.7,
    top_p: 0.95,
    max_tokens: 8192
  })
});
```

## Migration from Gemini

### Files Changed

- ✅ `resources/js/services/openrouterAI.js` (NEW)
- ✅ `resources/js/services/geminiAI.js` (backwards compatibility wrapper)
- ✅ `resources/js/modules/ai-tasks.js`
- ✅ `config/services.php`
- ✅ `.env.example`
- ✅ `.env.production`
- ✅ `vercel.json`

### Backwards Compatibility

- Old code using `window.geminiAI` still works
- `geminiAI.js` automatically redirects to `openrouterAI.js`
- Gradual migration path available

## Performance Comparison

### Token Usage (Rough Estimates)

For a typical task generation with 5 requirements:

| Model | Tokens | Cost | Speed |
|-------|--------|------|-------|
| GPT-3.5-turbo | ~1,500 | $0.003 | Fast |
| Claude 3 Haiku | ~1,500 | $0.0004 | Fast |
| GPT-4 Turbo | ~1,500 | $0.05 | Slower |
| Claude 3 Sonnet | ~1,500 | $0.006 | Medium |

## Best Practices

1. **Use GPT-3.5-turbo by default** - Fast, cheap, reliable
2. **Monitor your credits** - Set up billing alerts
3. **Test with small projects first** - Before using in production
4. **Log responses** - Store AI responses for auditing
5. **Handle errors gracefully** - Always have fallback options
6. **Update model periodically** - Try newer models as they release

## Support & Resources

- **OpenRouter Status:** https://status.openrouter.ai/
- **Discord Community:** https://discord.gg/openrouter
- **GitHub Issues:** https://github.com/openrouter/openrouter-site/issues

---

**GrowDev AI Integration:** OpenRouter ✓
**Last Updated:** May 2, 2026
**Status:** Active & Ready
