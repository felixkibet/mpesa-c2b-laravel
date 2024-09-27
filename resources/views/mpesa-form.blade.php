<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MPesa C2B API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body>
    <div class="container mt-2 mx-auto">
        <h3 class="mb-3 text-center">MPesa C2B API Integration</h3>

        <!-- Generate Token -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Generate Token</h5>
            </div>
            <div class="card-body">
                <button class="btn btn-primary btn-custom" id="generate-token">
                    <i class="fas fa-key"></i> Generate Token
                </button>
                <p class="mt-3 result-box" id="token-result"></p>
            </div>
        </div>

        <!-- Register URLs -->
        <div class="card mb-3">
            <div class="card-header">
                <h5>Register Validation and Confirmation URLs</h5>
            </div>
            <div class="card-body">
                <form id="register-urls-form">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-custom">
                        <i class="fas fa-check"></i> Register URLs
                    </button>
                </form>
                <p class="mt-3 result-box" id="register-result"></p>
            </div>
        </div>

        <!-- Simulate Transaction -->
        <div class="card">
            <div class="card-header">
                <h5>Simulate Transaction</h5>
            </div>
            <div class="card-body">
                <form id="simulate-transaction-form">
                    @csrf
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-custom">
                        <i class="fas fa-sync-alt"></i> Simulate
                    </button>
                </form>
                <p class="mt-3 result-box" id="simulate-result"></p>
            </div>
        </div>
    </div>

    @vite('resources/js/app.js')

    {{-- <script>
        // Token for CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let accessToken = ''; // To store the token after generation

        axios.defaults.baseURL = 'https://0320-197-237-97-236.ngrok-free.app';


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
    </script> --}}
</body>

</html>
