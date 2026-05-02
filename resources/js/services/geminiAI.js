/**
 * DEPRECATED: Gemini API has been replaced with OpenRouter
 * 
 * This file is kept for backwards compatibility.
 * Please use openrouterAI.js instead.
 * 
 * @deprecated Use 'openrouterAI.js' instead
 * @see resources/js/services/openrouterAI.js
 */

import { openrouterAI } from './openrouterAI.js';

// Re-export OpenRouter service as geminiAI for backwards compatibility
export const geminiAI = openrouterAI;
export default openrouterAI;
