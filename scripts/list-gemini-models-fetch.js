
const apiKey = 'AIzaSyCWK2g9CeDcvQThgaHdWKSIwvJRbbV-Ib8';
const url = `https://generativelanguage.googleapis.com/v1beta/models?key=${apiKey}`;

async function listModels() {
    try {
        const response = await fetch(url);
        const data = await response.json();
        console.log('Response:', JSON.stringify(data, null, 2));
    } catch (error) {
        console.error('Error:', error);
    }
}

listModels();
