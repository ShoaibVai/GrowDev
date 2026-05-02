import { openrouterAI } from '../services/openrouterAI.js';

// Make openrouterAI available globally for the AI tasks page
window.openrouterAI = openrouterAI;

// Also export as geminiAI for backwards compatibility
window.geminiAI = openrouterAI;

console.log('AI Tasks module loaded with OpenRouter AI integration');
