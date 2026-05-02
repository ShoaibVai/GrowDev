/**
 * AI Service using OpenRouter API
 * 
 * This service uses OpenRouter to access multiple AI models including:
 * - OpenAI GPT-4 / GPT-3.5
 * - Anthropic Claude
 * - Meta Llama
 * - Mistral
 * - And 200+ other models
 * 
 * For task generation, we use a high-quality model accessible via OpenRouter.
 * @see https://openrouter.ai/
 */
class OpenRouterAIService {
    constructor() {
        this.apiKey = import.meta.env.VITE_OPENROUTER_API_KEY || '';
        if (!this.apiKey) {
            console.warn('VITE_OPENROUTER_API_KEY is not configured. AI task generation will not work.');
        }
        // Use a reliable free model from OpenRouter. Override with VITE_OPENROUTER_MODEL.
        this.model = import.meta.env.VITE_OPENROUTER_MODEL || 'meta-llama/llama-3.1-8b-instruct:free';
        this.apiEndpoint = 'https://openrouter.ai/api/v1/chat/completions';
        this.timeout = 30000; // 30 second timeout
        this.initialized = true;
    }

    /**
     * Generate tasks using OpenRouter's AI capabilities
     * 
     * @param {Object} projectContext - Project details including requirements and team
     * @returns {Promise<Object>} Generated tasks array
     */
    async generateTasks(projectContext) {
        const systemPrompt = this.getSystemPrompt();
        const userPrompt = this.getUserPrompt(projectContext);

        try {
            // Abort after timeout so the loading screen doesn't hang forever
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), this.timeout);

            let response;
            try {
                response = await fetch(this.apiEndpoint, {
                    method: 'POST',
                    signal: controller.signal,
                    headers: {
                        'Authorization': `Bearer ${this.apiKey}`,
                        'Content-Type': 'application/json',
                        'HTTP-Referer': window.location.origin,
                        'X-Title': 'GrowDev Task Generator'
                    },
                    body: JSON.stringify({
                        model: this.model,
                        messages: [
                            { role: 'system', content: systemPrompt },
                            { role: 'user', content: userPrompt }
                        ],
                        temperature: 0.7,
                        max_tokens: 2048,
                    })
                });
            } finally {
                clearTimeout(timeoutId);
            }

            if (!response.ok) {
                const errorData = await response.json();
                const errorMessage = errorData.error?.message || `OpenRouter API error: ${response.status}`;
                throw new Error(errorMessage);
            }

            const data = await response.json();
            const content = data.choices?.[0]?.message?.content || '';
            
            if (!content) {
                throw new Error('Empty response from OpenRouter API');
            }

            const tasks = this.parseTasks(content);

            return {
                success: true,
                tasks: tasks,
                raw_response: content,
                model_used: data.model || this.model
            };
        } catch (error) {
            console.error('OpenRouter AI task generation failed:', error);
            return {
                success: false,
                error: error.message || 'Failed to generate tasks',
                tasks: []
            };
        }
    }

    /**
     * Get the system prompt for task generation
     */
    getSystemPrompt() {
        return `You are an expert project manager and software architect. Your task is to analyze project requirements and generate a structured list of development tasks.

For each task, you must specify:
1. title: Clear, actionable task title (max 100 chars)
2. description: Detailed description of what needs to be done
3. priority: Low, Medium, High, or Critical
4. estimated_hours: Estimated effort in hours (1-40)
5. required_role: One of the available roles that should handle this task
6. requirement_type: 'functional' or 'non_functional' if linked to a requirement
7. requirement_id: The ID of the linked requirement (if applicable)
8. dependencies: Array of task indices (0-based) this task depends on

Guidelines:
- Break down complex requirements into smaller, manageable tasks
- Consider both development and non-development tasks (testing, documentation, review)
- Balance workload across different roles
- Set realistic time estimates
- Create logical task dependencies
- Prioritize based on requirement priority and dependencies

Output Format:
Return ONLY a valid JSON array of task objects. No markdown, no explanation, just the JSON array.`;
    }

    /**
     * Get the user prompt with project context
     */
    getUserPrompt(projectContext) {
        const json = JSON.stringify(projectContext, null, 2);
        
        return `Generate development tasks for the following project:

${json}

Return ONLY a valid JSON array of task objects with this exact structure (no markdown, no code blocks):
[
  {
    "title": "string",
    "description": "string",
    "priority": "Low|Medium|High|Critical",
    "estimated_hours": number,
    "required_role": "string (from available_roles)",
    "requirement_type": "functional|non_functional|null",
    "requirement_id": number|null,
    "dependencies": [number]
  }
]`;
    }

    /**
     * Parse tasks from AI response
     */
    parseTasks(content) {
        // Clean up the response - remove markdown code blocks if present
        let cleaned = content.trim();
        cleaned = cleaned.replace(/^```json?\s*/gm, '');
        cleaned = cleaned.replace(/```\s*$/gm, '');
        cleaned = cleaned.trim();

        try {
            const tasks = JSON.parse(cleaned);

            if (!Array.isArray(tasks)) {
                throw new Error('AI response is not a valid task array');
            }

            // Validate and normalize each task
            return tasks.map((task, index) => ({
                index: index,
                title: this.validateString(task.title || '', 255),
                description: task.description || '',
                priority: this.validatePriority(task.priority || 'Medium'),
                estimated_hours: this.validateInt(task.estimated_hours || 4, 1, 200),
                required_role: task.required_role || 'Full Stack Developer',
                requirement_type: this.validateRequirementType(task.requirement_type || null),
                requirement_id: task.requirement_id || null,
                dependencies: task.dependencies || []
            }));
        } catch (error) {
            console.error('Failed to parse AI response:', error);
            throw new Error('Failed to parse AI response as JSON: ' + error.message);
        }
    }

    /**
     * Validate string field
     */
    validateString(value, maxLength) {
        const str = String(value || '').trim();
        return str.substring(0, maxLength);
    }

    /**
     * Validate priority field
     */
    validatePriority(priority) {
        const valid = ['Low', 'Medium', 'High', 'Critical'];
        return valid.includes(priority) ? priority : 'Medium';
    }

    /**
     * Validate integer field
     */
    validateInt(value, min, max) {
        const num = parseInt(value, 10) || min;
        return Math.max(min, Math.min(max, num));
    }

    /**
     * Validate requirement type
     */
    validateRequirementType(type) {
        if (type === 'functional' || type === 'non_functional') {
            return type;
        }
        return null;
    }
}

// Export singleton instance
export const openrouterAI = new OpenRouterAIService();
export default openrouterAI;
