
const https = require('https');

const apiKey = 'AIzaSyCWK2g9CeDcvQThgaHdWKSIwvJRbbV-Ib8';
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
