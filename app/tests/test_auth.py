import json
from app.tests.confest import app, client

def test_register_successful(client):
    # Dapat makapag register ang bagong user kapag complete ang data 

    payload = {
        "username": "test123",
        "first_name": "Test",
        "last_name": "user",
        "email": "test@gmail.com",
        "password": "password_test"
    }

    # mag POST sa register endpoint
    response = client.post('/api/auth/register',
                           data=json.dumps(payload),
                           content_type='application/json')
    
    # Assert e-verify kung tama ang ibabalik na API
    assert response.status_code == 201
    assert b"User registered successfully" in response.data

def test_login_successful(client):
    # Dapat makapag log in kapag tama ang credentials

    # 1. Mag register muna ang user na gagamitin sa login test
    payload_register = {
        "username": "loginuser",
        "first_name": "Login",
        "last_name": "Test",
        "email": "login@example.com",
        "password": "correct_password"
    }

    client.post('/api/auth/register', data=json.dumps(payload_register), content_type='application/json')

    # 2. Subukan mag login gamit ang tamang credentials
    payload_login = {
        "username": "loginuser",
        "password": "correct_password"
    }

    response = client.post('/api/auth/login',
                           data=json.dumps(payload_login),
                           content_type='application/json')

    data = json.loads(response.data)

    # assert
    assert response.status_code == 200
    assert data['message'] == "Login successful"
    assert data['user']['username'] == "loginuser"

def test_login_failed_wrong_password(client):
    # dapat mag error 404 if mali ang password

    payload_login = {
        "username": "admin", # Default admin mula sa seeding logic kung pumasok
        "password": "maling_password_ito"
    }

    response = client.post('/api/auth/login',
                           data=json.dumps(payload_login),
                           content_type='application/json')
    
    assert response.status_code == 401
    assert b"Invalid username or password" in response.data