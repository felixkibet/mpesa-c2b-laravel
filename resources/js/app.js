import './bootstrap';

//Token for CSRF
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let accessToken = ''; // To store the token after generation

// Generate Token
document.getElementById('generate-token').addEventListener('click', async () => {
    try {
        const response = await axios.get('/generate-token');
        accessToken = response.data.token;
        document.getElementById('token-result').textContent = 'Token: ' + accessToken;
        document.getElementById('token-result').style.display = 'block';
    } catch (error) {
        document.getElementById('token-result').textContent = 'Error generating token';
        document.getElementById('token-result').style.display = 'block';
    }
});

// Register URLs
document.getElementById('register-urls-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!accessToken) {
        document.getElementById('register-result').textContent = 'Please generate token first.';
        document.getElementById('register-result').style.display = 'block';
        return;
    }
    try {
        const response = await axios.post('/register-urls', {
            token: accessToken
        }, {
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });
        document.getElementById('register-result').textContent = response.data.success || response.data.error;
        document.getElementById('register-result').style.display = 'block';
    } catch (error) {
        document.getElementById('register-result').textContent = 'Error registering URLs';
    }
});

// Simulate Transaction
document.getElementById('simulate-transaction-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!accessToken) {
        document.getElementById('simulate-result').textContent = 'Please generate token first.';
        document.getElementById('simulate-result').style.display = 'block';
        return;
    }
    const amount = document.getElementById('amount').value;
    const phoneNumber = document.getElementById('phone_number').value;

    try {
        const response = await axios.post('/simulate', {
            token: accessToken,
            amount: amount,
            phone_number: phoneNumber
        }, {
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });
        document.getElementById('simulate-result').textContent = response.data.success || response.data.error;
        document.getElementById('simulate-result').style.display = 'block';
    } catch (error) {
        document.getElementById('simulate-result').textContent = 'Error simulating transaction';
        document.getElementById('simulate-result').style.display = 'block';
    }
});
