/**
 * AI Service using Google Gemini API
 * 
 * This service uses Google's Gemini API to generate development tasks
 * based on project requirements and team composition.
 */
class GeminiAIService {
    constructor() {
        this.apiKey = 'AIzaSyCWK2g9CeDcvQThgaHdWKSIwvJRbbV-Ib8';
        this.model = 'gemini-flash-latest';
        this.apiEndpoint = `https://generativelanguage.googleapis.com/v1beta/models/${this.model}:generateContent`;
        this.initialized = true;
    }

    /**
     * Generate tasks using Gemini's AI capabilities
     * 
     * @param {Object} projectContext - Project details including requirements and team
     * @returns {Promise<Object>} Generated tasks array
     */
    async generateTasks(projectContext) {
        const systemPrompt = this.getSystemPrompt();
        const userPrompt = this.getUserPrompt(projectContext);
        
        const fullPrompt = `${systemPrompt}\n\n${userPrompt}`;

        try {
            const response = await fetch(`${this.apiEndpoint}?key=${this.apiKey}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    contents: [{
                        parts: [{
                            text: fullPrompt
                        }]
                    }],
                    generationConfig: {
                        temperature: 0.7,
                        topK: 40,
                        topP: 0.95,
                        maxOutputTokens: 8192,
                    }
                })
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error?.message || `Gemini API error: ${response.status}`);
            }

            const data = await response.json();
            const content = data.candidates?.[0]?.content?.parts?.[0]?.text || '';
            
            if (!content) {
                throw new Error('Empty response from Gemini API');
            }

            const tasks = this.parseTasks(content);

            return {
                success: true,
                tasks: tasks,
                raw_response: content
            };
        } catch (error) {
            console.error('Gemini AI task generation failed:', error);
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

Return a JSON array of task objects with the structure:
[
  {
    "title": "string",
    "description": "string",
    "priority": "Low|Medium|High|Critical",
    "estimated_hours": number,
    "required_role": "string (from available_roles)",
    "requirement_type": "functional|non_functional|null",
    "requirement_id": number|null,
    "dependencies": [number] (indices of dependent tasks)
  }
]`;
    }

    /**
     * Parse tasks from AI response
     */
    parseTasks(content) {
        // Clean up the response - remove markdown code blocks if present
        let cleaned = content.trim();
        cleaned = cleaned.replace(/^```json?\s*/m, '');
        cleaned = cleaned.replace(/```\s*$/m, '');
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
export const geminiAI = new GeminiAIService();
// Keep puterAI for backward compatibility
export const puterAI = geminiAI;
export default geminiAI;
