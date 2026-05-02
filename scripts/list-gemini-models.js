
const https = require('https');
const dotenv = require('dotenv');

// Load environment variables from .env file
dotenv.config();

const apiKey = process.env.GEMINI_API_KEY;
if (!apiKey) {
    console.error('Error: GEMINI_API_KEY is not set in .env file');
    process.exit(1);
}
const url = `https://generativelanguage.googleapis.com/v1beta/models?key=${apiKey}`;

https.get(url, (res) => {
  let data = '';
  res.on('data', (chunk) => {
    data += chunk;
  });
  res.on('end', () => {
    try {
      const response = JSON.parse(data);
      console.log('Available Models:');
      if (response.models) {
        response.models.forEach(model => {
            if (model.supportedGenerationMethods && model.supportedGenerationMethods.includes('generateContent')) {
                console.log(`- ${model.name} (Version: ${model.version})`);
            }
        });
      } else {
          console.log(JSON.stringify(response, null, 2));
      }
    } catch (e) {
      console.error(e.message);
    }
  });
}).on('error', (err) => {
  console.error('Error: ' + err.message);
});
