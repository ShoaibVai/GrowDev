/**
 * AI Service using backend proxy (secure)
 *
 * All AI API calls are proxied through the Laravel backend so
 * API keys never reach the browser. The backend holds the key
 * and makes the actual OpenRouter API call server-side.
 */
class OpenRouterAIService {
    constructor() {
        this.apiEndpoint = '/api/ai/generate-tasks';
        this.timeout = 30000;
        this.initialized = true;
    }

    async generateTasks(projectContext) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), this.timeout);

            let response;
            try {
                response = await fetch(this.apiEndpoint, {
                    method: 'POST',
                    signal: controller.signal,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        project_context: projectContext,
                    }),
                });
            } finally {
                clearTimeout(timeoutId);
            }

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || `AI generation failed: ${response.status}`);
            }

            const data = await response.json();
            return {
                success: true,
                tasks: data.tasks || [],
                raw_response: data.raw_response || '',
                model_used: data.model_used || '',
            };
        } catch (error) {
            console.error('AI task generation failed:', error);
            return {
                success: false,
                error: error.message || 'Failed to generate tasks',
                tasks: [],
            };
        }
    }
}

export const openrouterAI = new OpenRouterAIService();
export default openrouterAI;
